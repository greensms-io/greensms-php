<?php

namespace GreenSms\Utils;

use GreenSms\Utils\Str;

class Version
{
    const VERSIONS = [
    'v1' => 'v1'
  ];

    public static function getVersion($version)
    {
        if (Str::isNullOrEmpty($version)) {
            return self::VERSIONS['v1'];
        }
        $version = strtolower($version);

        if (!array_key_exists($version, self::VERSIONS)) {
            throw new Exception('Invalid Version');
        }

        return self::VERSIONS[$version];
    }
}
