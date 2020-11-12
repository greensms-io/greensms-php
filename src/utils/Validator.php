<?php

namespace GreenSms\Utils;

use Valitron\Validator as ValitronValidator;
use GreenSms\Http\RestException;

class Validator
{
    public static function validate($schema, $data)
    {
        // var_dump($schema);
        // var_dump($data);

        $validator = new ValitronValidator($data);
        $validator->rules($schema);

        if ($validator->validate()) {
            return null;
        } else {
            $validationException = new RestException('Validation Error', 0);
            $validationException->setParams($validator->errors());
            throw $validationException;
        }
    }
}
