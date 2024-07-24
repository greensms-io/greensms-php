<?php

namespace GreenSMS;

use Exception;
use GreenSMS\Api\MethodInvoker;
use GreenSMS\Api\ModuleLoader;
use GreenSMS\Http\RestClient;
use GreenSMS\IdeHelper\Account;
use GreenSMS\Utils\Url;
use GreenSMS\Utils\Version;

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
          'baseUrl' => Url::baseUrl(),
          'restClient' => $this->getHttpClient([
            'useCamelCase' => $this->camelCaseResponse
          ]),
          'version' => Version::getVersion($this->version)
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
}
