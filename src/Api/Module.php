<?php

namespace GreenSMS\Api;

use GreenSMS\Utils\Validator;

class Module
{
    protected $restClient;
    protected $moduleSchema;
    protected $params;
    protected $uri;
    protected $preSendHandler;

    public function __construct($restClient, $moduleSchema, $options, $uri = null, $preSendHandler = null)
    {
        $this->restClient = $restClient;
        $this->moduleSchema = $moduleSchema;
        $this->params = $options;
        $this->uri = $uri;
        $this->preSendHandler = $preSendHandler;
    }

    public function apiFunction($data = [])
    {
        if (!is_null($this->preSendHandler)) {
            call_user_func_array($this->preSendHandler, [&$data, $this->uri, $this->params['method']]);
        }

        if ($this->moduleSchema) {
            $validationResult = Validator::validate($this->moduleSchema, $data);
        }

        $requestParams = $this->params;
        $requestParams['params'] = $data;
        $response = $this->restClient->request($requestParams);
        return $response;
    }
}
