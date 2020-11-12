<?php

namespace GreenSms\Utils;

use Valitron\Validator as ValitronValidator;

class Validator {
  static function validate($schema, $data) {
    $errorResult = null;
    $validator = new ValitronValidator($data);
    $validator->rules($schema);

    if($v->validate()) {
      return null;
    } else {
      $errors = $v->errors();
    }

    print_r($errors);

  }
}