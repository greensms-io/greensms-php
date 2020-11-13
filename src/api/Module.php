<?php

namespace GreenSms\Api;

use GreenSms\Utils\Validator;

class Module
{
    protected $restClient;
    protected $moduleSchema;
    protected $params;

    public function __construct($restClient, $moduleSchema, $options)
    {
        $this->restClient = $restClient;
        $this->moduleSchema = $moduleSchema;
        $this->params = $options;
    }

    public function apiFunction($data = [])
    {
        if ($this->moduleSchema) {
            $validationResult = Validator::validate($this->moduleSchema, $data);
        }

        $requestParams = $this->params;
        $requestParams['params'] = $data;
        $response = $this->restClient->request($requestParams);
        return $response;
    }
}
