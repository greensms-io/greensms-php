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

}
