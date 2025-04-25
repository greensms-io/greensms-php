<?php

namespace GreenSMS\Utils;

use Valitron\Validator as ValitronValidator;
use GreenSMS\Http\RestException;

class Validator
{
    public static function validate($schema, $data)
    {
        $validator = new ValitronValidator($data);
        $validator->mapFieldsRules($schema);

        if ($validator->validate()) {
            return null;
        } else {
            $validationException = new RestException('Validation Error', 0);
            $validationException->setParams($validator->errors());
            throw $validationException;
        }
    }
}
