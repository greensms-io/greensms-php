<?php

namespace GreenSms\Utils;

use Valitron\Validator as ValitronValidator;
use GreenSms\Http\RestException;

class Validator
{
    public static function validate($schema, $data)
    {
        $validator = new ValitronValidator($data);
        $validator->rules($schema);

        if ($v->validate()) {
            return null;
        } else {
            $validationException = new RestException('Validation Error', 0);
            $validationException->setParams($v->errors());
            throw $validationException;
        }
    }
}
