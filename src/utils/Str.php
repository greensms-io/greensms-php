<?php

namespace GreenSms\Utils;

class Str {

  static function isNullOrEmpty($str) {
    if(is_null($str)) {
      return true;
    }

    if(strlen($str) === 0) {
      return true;
    }

    return false;
  }

  static function camelizeKeys($array) {
    $result = [];

    array_walk_recursive($array, function ($value, &$key) use (&$result) {
        $newKey = preg_replace_callback('/_([a-z])/', function ($matches) {
            return strtoupper($matches[1]);
        }, $key);

        $result[$newKey] = $value;
    });

    return $result;
  }

}
