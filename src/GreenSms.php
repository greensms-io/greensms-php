<?php

namespace GreenSms;

use GreenSms\Utils\Url;

class GreenSms {

  protected $token;
  protected $user;
  protected $pass;
  protected $version;
  protected $useTokenForRequests = true;
  protected $useCamelCaseResponse = false;

  function __construct($options = []) {
    if(is_null($options)) {
      $options = [];
    }

    if(array_key_exists('token', $options)) {
      $this->token = $options['token'];
    }


    if(array_key_exists( 'user', $options)) {
      $this->user = $options['user'];
    }

    if(array_key_exists( 'pass', $options)) {
      $this->pass = $options['pass'];
    }

    if(array_key_exists( 'useTokenForRequests', $options)) {
      $this->useTokenForRequests = $options['useTokenForRequests'];
    }

    if(array_key_exists( 'camelCaseResponse', $options)) {
      $this->useCamelCaseResponse = $options['camelCaseResponse'];
    }

    if(array_key_exists( 'version', $options)) {
      $this->version = $options['version'];
    }

    if(is_null($this->token)) {
      $this->token = getenv('GREENSMS_TOKEN');
    }

    if(is_null($this->token) && is_null($this->user)) {
      $user = getenv('GREENSMS_USER');
    }

    if(is_null($this->token) && is_null($this->pass)) {
      $pass = getenv('GREENSMS_PASS');
    }

    if(is_null($this->token) && (is_null($this->user) || is_null($this->pass))) {
      throw new Exception('Either User/Pass or Auth Token is required!');
    }

    $sharedOptions = [
      'useTokenForRequests' => $this->useTokenForRequests,
      'baseUrl' => Url::baseUrl(),
      'restClient' => $this->getHttpClient([
        'useCamelCase' => $this->useCamelCaseResponse
      ])
    ];

    echo(Url::buildUrl($sharedOptions['baseUrl'], ['account', 'balance']));
  }

  function addModules() {

  }

  function getHttpClient() {

  }
}