<?php

namespace GreenSms\Api;

class Schema
{
    private static function getToSchema()
    {
        $toSchema = ['required',['lengthMin', 11], ['lengthMax', 14], ['regex', '/^[0-9]+$/']];
        return $toSchema;
    }

    private static function getIdSchema()
    {
        $toSchema = ['required',['lengthMin', 36], ['lengthMax', 36]];
        return $toSchema;
    }

    private static function getCommonSchema()
    {
        $commonSchema = [
          'v1' => [
            'send' => [
              'to' => self::getToSchema()
            ],
            'status' => [
              'id' => self::getIdSchema(),
              'extended' => ['boolean']
            ]
          ]
        ];

        return $commonSchema;
    }

    public static function getSchema()
    {
        $commonSchema = self::getCommonSchema();
        $toSchema = self::getToSchema();

        $schema = [
          'account' => [
            'v1' => [
              'token' => [
                'expire' => ['integer', ['min', 0]],
              ]
            ]
          ],
          'call' => $commonSchema,
          'hlr' => $commonSchema,
          'general' => [],
          'whois' => [
            'v1' => [
              'lookup' => [
                'to' => $toSchema
              ]
            ],
          ],
          'voice' => array_merge_recursive($commonSchema, [
            'v1' => [
              'send' => [
                'txt' => ['required', ['lengthMin', 1], ['lengthMax', 5], ['regex', '/^[0-9]+$/']],
                'lang' => [['subset', ['ru', 'en']]]
              ]
            ]
          ]),
          'pay' => array_merge_recursive($commonSchema, [
            'v1' => [
              'send' => [
                'amount' => [ 'required','integer', ['min', 1]],
                'tag' => ['alphaNum']
              ],
            ]
          ]),
          'sms' => array_merge_recursive($commonSchema, [
            'v1' => [
              'send' => [
                'txt' => ['required', ['lengthMin', 1]],
                'from' => ['alphaNum'],
                'tag' => ['alphaNum'],
              ]
            ]
          ]),
          'viber' => array_merge_recursive($commonSchema, [
            'v1' => [
              'send' => [
                'txt' => ['required', ['lengthMin', 1]],
                'from' => ['alphaNum'],
                'cascase' => [['subset', ['sms', 'voice']]],
              ]
            ]
          ]),
          'social' => array_merge_recursive($commonSchema, [
            'v1' => [
              'send' => [
                'txt' => ['required', ['lengthMin', 1]],
                'from' => ['required', 'alphaNum'],
                'tag' => ['alphaNum'],
              ]
            ]
          ]),
        ];

        return $schema;
    }
}
