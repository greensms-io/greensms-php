<?php

namespace GreenSms\Api;

use GreenSms\Api\Modules;
use GreenSms\Utils\Url;

class ModuleLoader {

  protected $moduleMap = [];

  function registerModules($sharedOptions, $filters = null) {

    if(is_null($filters)) {
      $filters = [];
    }

    $currentVersion = $sharedOptions['version'];
    $modules = Modules::getModules();

    foreach ($modules as $moduleName => $moduleInfo) {

      if(!array_key_exists($moduleName, $this->moduleMap)) {
        $this->moduleMap[$moduleName] = [];
      }

      $moduleVersions = $moduleInfo['versions'];
      $isStaticModule = ($filters['loadStatic']  && $moduleInfo['static']);

      if($isStaticModule) {
        continue;
      }

      foreach ($moduleVersions as $version => $versionFunctions) {
        if(!array_key_exists($version, $this->moduleMap[$moduleName])) {
          $this->moduleMap[$moduleName][$version] = [];
        }

        foreach ($versionFunctions as $functionName => $functionDefinition) {
          if(!array_key_exists($functionName, $this->moduleMap[$moduleName][$version])) {
            $this->moduleMap[$moduleName][$version][$functionName] = [];
          }

          $moduleSchema = null;
          $schemaExists = $moduleInfo['schema']
                            && $moduleInfo['schema'][$version]
                            && $moduleInfo['schema'][$version][$functionName];
          if($schemaExists) {
            $moduleSchema = $moduleInfo['schema'][$version][$functionName];
          }

          $urlParts = [];
          if(!$moduleInfo['static']) {
            $urlParts.push($moduleName);
          }

          $urlParts.push($functionName);
          $apiUrl = Url::buildUrl($sharedOptions['baseUrl'], $urlParts);

          if($version === $currentVersion) {
            $this->moduleMap[$moduleName][$functionName] = $this->moduleMap[$moduleName][$version][$functionName];
          }

          if($moduleInfo['static']) {
            $this->moduleMap[$functionName] = $this->moduleMap[$moduleName][$version][$functionName];
            unset($this->moduleMap[$moduleName]);
          }
        }
      }
    }

    return $this->moduleMap;
  }
}