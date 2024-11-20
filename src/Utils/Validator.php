<?php

namespace GreenSMS\Utils;

use Valitron\Validator as ValitronValidator;
use GreenSMS\Http\RestException;

class Validator
{
    public static function validate($schema, $data)
    {
        ValitronValidator::addRule('ipsCommaSeparator', function($field, $value, array $params, array $fields) {
            if ($fields['type'] == 'IP') {
                foreach (explode(',', $value) as $val) {
                    if (!filter_var($val, FILTER_VALIDATE_IP)) {
                        return false;
                    }
                }
            }

            return true;
        }, 'IP incorrect');
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
