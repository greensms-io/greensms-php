<?php

namespace GreenSms\Http;

class RestClient {

  protected $token;
  protected $defaultData;
  protected $defaultParams;
  protected $useCamelCase = false;
  protected $ch;

  function __construct($options) {
    if(is_null($options)) {
      $options = [];
    }

    if(array_key_exists('token', $options)) {
      $this->token = $options['token'];
    }

    if(array_key_exists('defaultData', $options)) {
      $this->defaultData = $options['defaultData'];
    }

    if(array_key_exists('defaultParams', $options)) {
      $this->defaultParams = $options['defaultParams'];
    }

    if(array_key_exists('useCamelCase', $options)) {
      $this->useCamelCase = $options['useCamelCase'];
    }

    $this->ch = curl_init();

  }

  function request($options) {

    if(is_null($options)) {
      $options = [];
    }

    if(!array_key_exists('method', $options)) {
      throw new Exception('Http Method is required!');
    }

    if(!array_key_exists('url', $options)) {
      throw new Exception('URL is required!');
    }

    if(strtolower($options['method']) === 'post') {
      curl_setopt($this->ch, CURLOPT_POST, 1);
    }

    $headers = [];
    if(array_key_exists('token', $options)) {
      $headers['Authorization'] = 'Bearer '. $options['token'];
    }

    if(array_key_exists('headers', $options))  {
      $headers = array_merge($headers, $options['headers']);
    }

    if (!empty($headers)) {
      $headers_arr = [];
      foreach ($headers_arr as $k => $v) {
          if (is_array($v)) {
              foreach ($v as $val) {
                  $headers_arr[] = "$k: $val";
              }
          } else {
              $headers_arr[] = "$k: $v";
          }
      }
      curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers_arr);
    }


    $params_str = "";
    $params = [];
    if(!empty($this->defaultParams)) {
      $params = $this->defaultParams;
    }
    if(array_key_exists('params', $options)) {
      $params = array_merge($params, $options['params']);
    }

    $url = $options['url'];
    $params_str = http_build_query($params);
    if(strlen($params_str) > 0) {
      $url .= (parse_url($url, PHP_URL_QUERY) ? '&' : '?') . $params_str;
    }

    echo "URL" . $url;


    curl_setopt($this->ch, CURLOPT_URL, $url);
    curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($this->ch, CURLOPT_HEADER, 1);

  }
}