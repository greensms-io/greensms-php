<?php

namespace GreenSms\Api;

use GreenSms\Api\Modules;
use GreenSms\Utils\Url;
use GreenSms\Utils\Helpers;

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
      $processStaticOnly = Helpers::keyExistsAndTrue('loadStatic', $filters) && Helpers::keyExistsAndTrue('static', $moduleInfo);

      if($processStaticOnly) {
        continue;
      }

      $isStaticModule = Helpers::keyExistsAndTrue('static', $moduleInfo);



      foreach ($moduleVersions as $version => $versionFunctions) {
        if(!array_key_exists($version, $this->moduleMap[$moduleName])) {
          $this->moduleMap[$moduleName][$version] = [];
        }

        foreach ($versionFunctions as $functionName => $functionDefinition) {
          if(!array_key_exists($functionName, $this->moduleMap[$moduleName][$version])) {
            $this->moduleMap[$moduleName][$version][$functionName] = [];
          }

          $moduleSchema = null;
          $schemaExists = self::doesSchemaExists($moduleInfo, $version, $functionName);
          if($schemaExists) {
            $moduleSchema = $moduleInfo['schema'][$version][$functionName];
          }

          $urlParts = [];
          if($isStaticModule) {
            array_push($urlParts, $moduleName);
          }

          array_push($urlParts, $functionName);
          $apiUrl = Url::buildUrl($sharedOptions['baseUrl'], $urlParts);

          if($version === $currentVersion) {
            $this->moduleMap[$moduleName][$functionName] = $this->moduleMap[$moduleName][$version][$functionName];
          }

          if($isStaticModule) {
            $this->moduleMap[$functionName] = $this->moduleMap[$moduleName][$version][$functionName];
            unset($this->moduleMap[$moduleName]);
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
}