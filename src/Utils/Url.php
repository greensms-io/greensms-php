<?php

namespace GreenSMS\Utils;

use Exception;

const BASE_URL = 'https://api3.greensms.io';

class Url
{
    public static function baseUrl(): string
    {
        return BASE_URL;
    }

    public static function buildUrl($baseUrl, $parts): string
    {
        if (Helpers::isNullOrEmpty($baseUrl)) {
            throw new Exception('Base URL cannot be empty');
        }

        array_unshift($parts, $baseUrl);
        array_walk_recursive($parts, 'GreenSMS\Utils\Url::stripTrailingSlash');

        return implode('/', $parts);
    }

    public static function stripTrailingSlash(&$component)
    {
        $component = rtrim($component, '/');
    }
}
