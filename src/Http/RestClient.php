<?php
namespace GreenSMS\Http;

use GreenSMS\Utils\Helpers;
use GreenSMS\Http\RestException;

class RestClient {
	protected $token;
	protected $defaultData;
	protected $defaultParams;
	protected $useCamelCase = false;
	protected $ch;

	public function __construct( $options ) {
		if ( is_null( $options ) ) {
			$options = [];
		}

		if ( array_key_exists( 'token', $options ) ) {
			$this->token = $options['token'];
		}

		if ( array_key_exists( 'defaultData', $options ) ) {
			$this->defaultData = $options['defaultData'];
		}

		if ( array_key_exists( 'defaultParams', $options ) ) {
			$this->defaultParams = $options['defaultParams'];
		}

		if ( array_key_exists( 'useCamelCase', $options ) ) {
			$this->useCamelCase = $options['useCamelCase'];
		}
	}

	public function request( $options ) {
		if ( is_null( $options ) ) {
			$options = [];
		}

		if ( ! array_key_exists( 'method', $options ) ) {
			throw new Exception( 'Http Method is required!' );
		}

		if ( ! array_key_exists( 'url', $options ) ) {
			throw new Exception( 'URL is required!' );
		}

		$this->ch = curl_init();

		if ( strtolower( $options['method'] ) == 'post' ) {
			curl_setopt( $this->ch, CURLOPT_POST, 1 );
		} else {
			curl_setopt( $this->ch, CURLOPT_POST, 0 );
		}

		$params = [];
		if ( ! empty( $this->defaultParams ) ) {
			$params = $this->defaultParams;
		}
		if ( array_key_exists( 'params', $options ) ) {
			$params = array_merge( $params, $options['params'] );
		}

		$url = $options['url'];
		$params_str = http_build_query( $params );

		if ( strlen( $params_str ) > 0 ) {
			if ( strtolower( $options['method'] ) === 'post' ) {
				curl_setopt( $this->ch, CURLOPT_POSTFIELDS, json_encode( $params ) );
			} else {
				$url .= ( parse_url( $url, PHP_URL_QUERY ) ? '&' : '?' ) . $params_str;
			}
		}

		curl_setopt( $this->ch, CURLOPT_URL, $url );
		curl_setopt( $this->ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $this->ch, CURLOPT_HEADER, 0 );
		curl_setopt( $this->ch, CURLOPT_SSL_VERIFYHOST, 0 );
		curl_setopt( $this->ch, CURLOPT_SSL_VERIFYPEER, 0 );

		$headers = [
			'Content-Type' => 'application/json'
		];

		if ( $this->token ) {
			$headers['Authorization'] = 'Bearer ' . $this->token;
		}

		if ( array_key_exists( 'headers', $options ) ) {
			$headers = array_merge( $headers, $options['headers'] );
		}

		if ( ! empty( $headers ) ) {
			$headers_arr = [];
			foreach ( $headers as $k => $v ) {
				if ( is_array( $v ) ) {
					foreach ( $v as $val ) {
						$headers_arr[] = "$k: $val";
					}
				} else {
					$headers_arr[] = "$k: $v";
				}
			}
			curl_setopt( $this->ch, CURLOPT_HTTPHEADER, $headers_arr );
		}

		$apiResult = curl_exec( $this->ch );
		$response = json_decode( $apiResult, true );

		if ( curl_errno( $this->ch ) ) {
			$error = curl_error( $this->ch );
			$response = new RestException( $error->message, $error->code, $error->previous );
		}

		curl_close( $this->ch );

		if ( array_key_exists( 'error', $response ) ) {
			throw new RestException( $response['error'], $response['code'] );
		}

		if ( $this->useCamelCase ) {
			$response = Helpers::camelizeKeys( $response );
		}

		return (object) $response;
	}
}
