<?php

namespace GreenSMS\Utils;

use GreenSMS\Utils\Helpers;

class Version
{
    const VERSIONS = [
    'v1' => 'v1',
    'v1.0.1' => 'v1.0.1',
  ];

    public static function getVersion($version)
    {
        if (Helpers::isNullOrEmpty($version)) {
            return self::VERSIONS['v1'];
        }
        $version = strtolower($version);

        if (!array_key_exists($version, self::VERSIONS)) {
            throw new Exception('Invalid Version');
        }

        return self::VERSIONS[$version];
    }
}
