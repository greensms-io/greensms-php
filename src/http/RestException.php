<?php

namespace GreenSms\Http;

use \Exception;

class RestException extends Exception
{
    protected $errorType = '';
    protected $params = [];

    public function __constructor($message, $code = 0, $previous = null)
    {
        $errorMessage = $message;
        $this->name =  'RestException';
        parent::__construct($errorMessage, $code, $previous);
        $errorType = $this->getErrorType($this->code);
        $this->errorType = $errorType;
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function __toString() {
      $params = json_encode($this->params);
      return __CLASS__ . ": [{$this->code}]: {$this->message}\n{$this->getTraceAsString()}\n\n{$params}";
    }

    public function getErrorType($code)
    {
        switch ($code) {
      case 0:
        return 'AUTH_DECLINED';

      case 1:
        return 'MISSING_INPUT_PARAM';

      case 2:
        return 'INVALID_INPUT_PARAM';

      case 404:
        return 'NOT_FOUND';

      default:
        return 'INTERNAL_SERVER_ERROR';
    }
    }
}
