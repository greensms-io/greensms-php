<?php

namespace GreenSMS;

use BadFunctionCallException;
use Exception;
use GreenSMS\Api\MethodInvoker;
use GreenSMS\Api\ModuleLoader;
use GreenSMS\Http\RestClient;
use GreenSMS\IdeHelper\Account;
use GreenSMS\Utils\Url;
use GreenSMS\Utils\Version;
use Valitron\Validator;
use Valitron\Validator as ValitronValidator;

/**
 * @property Account $account
 */
class GreenSMS extends MethodInvoker
{
    protected $token;
    protected $user;
    protected $pass;
    protected $version;
    protected $useTokenForRequests = true;
    protected $camelCaseResponse = false;
    protected $baseUrl = null;

    public function __construct($options = [])
    {
        if (is_null($options)) {
            $options = [];
        }

        if (array_key_exists('token', $options)) {
            $this->token = $options['token'];
        }


        if (array_key_exists('user', $options)) {
            $this->user = $options['user'];
        }

        if (array_key_exists('pass', $options)) {
            $this->pass = $options['pass'];
        }

        if (array_key_exists('useTokenForRequests', $options)) {
            $this->useTokenForRequests = $options['useTokenForRequests'];
        }

        if (array_key_exists('camelCaseResponse', $options)) {
            $this->camelCaseResponse = $options['camelCaseResponse'];
        }

        if (array_key_exists('version', $options)) {
            $this->version = $options['version'];
        }
        
        if (array_key_exists('baseUrl', $options)) {
            $this->baseUrl = $options['baseUrl'];
        }        

        if (!$this->token) {
            $this->token = getenv('GREENSMS_TOKEN');
        }


        if (!$this->token && !$this->user) {
            $this->user = getenv('GREENSMS_USER');
        }

        if (!$this->token && !$this->pass) {
            $this->pass = getenv('GREENSMS_PASS');
        }

        if (!$this->token && (!$this->user || !$this->pass)) {
            throw new Exception('Either User/Pass or Auth Token is required!');
        }

        $sharedOptions = [
          'useTokenForRequests' => $this->useTokenForRequests,
          'baseUrl' => $this->baseUrl ?? Url::baseUrl(),
          'restClient' => $this->getHttpClient([
            'useCamelCase' => $this->camelCaseResponse
          ]),
          'version' => Version::getVersion($this->version),
          'preSendHandler' => $this->getPreSendHandler($options),
        ];

        $this->addModules($sharedOptions);
    }

    public function addModules($sharedOptions)
    {
        $moduleLoader = new ModuleLoader();
        $modules = $moduleLoader->registerModules($sharedOptions);
        foreach ($modules as $moduleName => $moduleTree) {
            $this->{$moduleName} = $moduleTree;
        }

        ValitronValidator::addRule('ipsCommaSeparator', function($field, $value, array $params, array $fields) {
            if ($fields['type'] == 'IP') {
                foreach (explode(',', $value) as $val) {
                    if (!filter_var($val, FILTER_VALIDATE_IP)) {
                        return false;
                    }
                }
            }

            return true;
        }, 'IP incorrect');

        Validator::addRule('commaSeparatedInStrict', function ($field, $value, array $params) {
            if (!is_string($value)) {
                return false;
            }

            $allowedValues = $params[0] ?? [];
            $items = explode(',', $value);

            foreach ($items as $item) {
                if (!in_array($item, $allowedValues, true)) {
                    return false;
                }
            }

            return true;
        }, 'must contain values from the list with comma: %s');
    }

    public function getHttpClient($args)
    {
        $defaultParams = [];

        if (!$this->token && $this->user) {
            $defaultParams['user'] = $this->user;
            $defaultParams['pass'] = $this->pass;
        }

        $httpParams = [
          'defaultParams' => $defaultParams,
          'defaultData' => [],
          'token' => $this->token
        ];

        $httpParams = array_merge($httpParams, $args);

        $restClient = new RestClient($httpParams);
        return $restClient;
    }

    private function getPreSendHandler($options): ?callable
    {
        if (array_key_exists('preSendHandler', $options)) {
            if (is_callable($options['preSendHandler'])) {
                return $options['preSendHandler'];
            } else {
                throw new BadFunctionCallException('Key `preSendHandler` must be callable');
            }
        }

        return null;
    }
}
