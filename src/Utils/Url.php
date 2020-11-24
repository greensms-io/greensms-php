<?php

namespace GreenSMS\Utils;

use \Exception;
use GreenSMS\Utils\Helpers;

const BASE_URL = 'https://api3.greensms.ru';

class Url
{
    public static function baseUrl()
    {
        return BASE_URL;
    }

    public static function buildUrl($baseUrl, $parts)
    {
        if (Helpers::isNullOrEmpty($baseUrl)) {
            throw new Exception('Base URL cannot be empty');
        }

        array_unshift($parts, $baseUrl);
        array_walk_recursive($parts, 'GreenSMS\Utils\Url::stripTrailingSlash');
        $url = implode('/', $parts);

        return $url;
    }

    public static function stripTrailingSlash(&$component)
    {
        $component = rtrim($component, '/');
    }
}
