<?php

namespace GreenSms\Api;

use GreenSms\Api\Modules;
use GreenSms\Api\Module;
use GreenSms\Utils\Url;
use GreenSms\Utils\Helpers;
use GreenSms\Api\MethodInvoker;

class ModuleLoader {

  protected $moduleMap = null;

  function registerModules($sharedOptions, $filters = null) {

    if(is_null($filters)) {
      $filters = [];
    }

    $this->moduleMap = new MethodInvoker;

    $currentVersion = $sharedOptions['version'];
    $modules = Modules::getModules();

    foreach ($modules as $moduleName => $moduleInfo) {

      if(!property_exists($this->moduleMap, $moduleName)) {
        $this->moduleMap->{$moduleName} = new MethodInvoker;
      }

      $moduleVersions = $moduleInfo['versions'];
      $processStaticOnly = Helpers::keyExistsAndTrue('loadStatic', $filters) && Helpers::keyExistsAndTrue('static', $moduleInfo);

      if($processStaticOnly) {
        continue;
      }

      $isStaticModule = Helpers::keyExistsAndTrue('static', $moduleInfo);



      foreach ($moduleVersions as $version => $versionFunctions) {
        if(!property_exists($this->moduleMap->{$moduleName}, $version)) {
          $this->moduleMap->{$moduleName}->{$version} = new MethodInvoker;
        }

        foreach ($versionFunctions as $functionName => $functionDefinition) {

          $moduleSchema = null;
          $schemaExists = self::doesSchemaExists($moduleInfo, $version, $functionName);
          if($schemaExists) {
            $moduleSchema = $moduleInfo['schema'][$version][$functionName];
          }

          $urlParts = [];
          if(!$isStaticModule) {
            array_push($urlParts, $moduleName);
          }

          array_push($urlParts, $functionName);
          $apiUrl = Url::buildUrl($sharedOptions['baseUrl'], $urlParts);

          $functionOptions = [
            'url' => $apiUrl,
            'definition' => $functionDefinition,
            'sharedOptions' => $sharedOptions,
            'moduleSchema' => $moduleSchema
          ];


          $this->moduleMap->{$moduleName}->{$version}->{$functionName} = $this->getFunctionInstance($functionOptions);

          if($version === $currentVersion) {
            $this->moduleMap->{$moduleName}->{$functionName} = $this->moduleMap->{$moduleName}->{$version}->{$functionName};
          }

          if($isStaticModule) {
            $this->moduleMap->{$functionName} = $this->moduleMap->{$moduleName}->{$version}->{$functionName};
            unset($this->moduleMap->{$moduleName});
          }
        }
      }
    }

    return $this->moduleMap;
  }

  private function doesSchemaExists($moduleInfo, $version, $functionName) {
    if(!array_key_exists('schema', $moduleInfo)) {
      return false;
    } else if(!array_key_exists($version, $moduleInfo['schema'])) {
      return false;
    } else if(!array_key_exists($functionName, $moduleInfo['schema'][$version])) {
      return false;
    }
    return true;
  }

  private function getFunctionInstance($options) {

    $restClient = $options['sharedOptions']['restClient'];
    $moduleSchema = $options['moduleSchema'];

    $requestArgs = [
      'url' => $options['url'],
      'method' => $options['definition']['method']
    ];

    $module = new Module($restClient, $moduleSchema, $requestArgs);
    $functionInstance = array($module, 'apiFunction');
    return $functionInstance;
  }
}