<?php

namespace GreenSMS\Api;

use GreenSMS\Utils\Url;
use GreenSMS\Utils\Helpers;

class ModuleLoader
{
    protected $moduleMap = null;

    public function registerModules($sharedOptions, $filters = null)
    {
        if (is_null($filters)) {
            $filters = [];
        }

        $this->moduleMap = new MethodInvoker;

        $currentVersion = $sharedOptions['version'];
        $modules = Modules::getModules();

        foreach ($modules as $moduleName => $moduleInfo) {
            if (!property_exists($this->moduleMap, $moduleName)) {
                $this->moduleMap->{$moduleName} = new MethodInvoker;
            }

            $moduleVersions = $moduleInfo['versions'];
            $processStaticOnly = Helpers::keyExistsAndTrue('loadStatic', $filters) && Helpers::keyExistsAndTrue('static', $moduleInfo);

            if ($processStaticOnly) {
                continue;
            }

            $isStaticModule = Helpers::keyExistsAndTrue('static', $moduleInfo);



            foreach ($moduleVersions as $version => $versionFunctions) {

                if ($version === 'v1') {
                    $this->addModuleMapV1($versionFunctions, $moduleInfo, $version, $isStaticModule, $moduleName, $sharedOptions, $currentVersion);
                } elseif ($version === 'v4.0.0') {
                    $this->addModuleMapV4_0_0($versionFunctions, $moduleInfo, $version, $isStaticModule, $moduleName, $sharedOptions, $currentVersion);
                }

            }
        }

        return $this->moduleMap;
    }

    private function doesSchemaExists($moduleInfo, $version, $functionName)
    {
        if (!array_key_exists('schema', $moduleInfo)) {
            return false;
        } elseif (!array_key_exists($version, $moduleInfo['schema'])) {
            return false;
        } elseif (!array_key_exists($functionName, $moduleInfo['schema'][$version])) {
            return false;
        }
        return true;
    }

    private function getFunctionInstance($options)
    {
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

    private function addModuleMapV1($versionFunctions, $moduleInfo, $version, $isStaticModule, $moduleName, $sharedOptions, $currentVersion)
    {
        if (!property_exists($this->moduleMap->{$moduleName}, $version)) {
            $this->moduleMap->{$moduleName}->{$version} = new MethodInvoker;
        }

        foreach ($versionFunctions as $functionName => $functionDefinition) {
            $moduleSchema = null;
            $schemaExists = self::doesSchemaExists($moduleInfo, $version, $functionName);
            if ($schemaExists) {
                $moduleSchema = $moduleInfo['schema'][$version][$functionName];
            }

            $urlParts = [];
            if (!$isStaticModule) {
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

            if ($version === $currentVersion) {
                $this->moduleMap->{$moduleName}->{$functionName} = $this->moduleMap->{$moduleName}->{$version}->{$functionName};
            }

            if ($isStaticModule) {
                $this->moduleMap->{$functionName} = $this->moduleMap->{$moduleName}->{$version}->{$functionName};
                unset($this->moduleMap->{$moduleName});
            }
        }
    }

    private function addModuleMapV4_0_0($functions, $moduleInfo, $version, $isStaticModule, $moduleName, $sharedOptions, $currentVersion)
    {

        foreach ($functions as $prop => $definitions) {
            if (!property_exists($this->moduleMap->{$moduleName}, $prop)) {
                $this->moduleMap->{$moduleName}->{$prop} = new MethodInvoker;
            }
            if (!property_exists($this->moduleMap->{$moduleName}->{$prop}, $version)) {
                $this->moduleMap->{$moduleName}->{$prop}->{$version} = new MethodInvoker;
            }

            foreach ($definitions as $functionName => $functionDefinition) {
                $urlParts = [];
                if (!$isStaticModule) {
                    array_push($urlParts, $moduleName);
                }

                array_push($urlParts, $prop);
                $apiUrl = Url::buildUrl($sharedOptions['baseUrl'], $urlParts);

                $functionOptions = [
                    'url' => $apiUrl,
                    'definition' => $functionDefinition,
                    'sharedOptions' => $sharedOptions,
                    'moduleSchema' => $this->getSchema($moduleInfo['schema'] ?? [], $version, $prop, $functionName),
                ];


                $this->moduleMap->{$moduleName}->{$prop}->{$version}->{$functionName} = $this->getFunctionInstance($functionOptions);
                $this->moduleMap->{$moduleName}->{$prop}->{$functionName} = $this->moduleMap->{$moduleName}->{$prop}->{$version}->{$functionName};

            }
        }
    }

    private function getSchema($schema, $version, $prop, $functionName): ?array
    {
        if (!array_key_exists($version, $schema)) {
            return null;
        } elseif (!array_key_exists($prop, $schema[$version])) {
            return null;
        } elseif (!array_key_exists($functionName, $schema[$version][$prop])) {
            return null;
        }

        return $schema[$version][$prop][$functionName];
    }
}
