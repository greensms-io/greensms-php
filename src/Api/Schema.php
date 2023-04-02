<?php

namespace GreenSMS\Api;

class Schema
{
    private static function getToSchema()
    {
        $toSchema = ['required',['lengthMin', 11], ['lengthMax', 14], ['regex', '/^[0-9]+$/']];
        return $toSchema;
    }

    private static function getIdSchema()
    {
        $idSchema = ['required',['lengthMin', 36], ['lengthMax', 36]];
        return $idSchema;
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
              'extended' => [['subset', ['true', 'false']]]
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
          'call' => array_merge_recursive($commonSchema, [
            'v1' => [
              'send' => [
                'voice' => [['subset', ['true', 'false']]],
                'tag' => [['lengthMax', 36]],
                'language' => [['subset', ['ru', 'en']]]
              ],
              'receive' => [
                'to' => self::getToSchema(),
                'toll_free' => [['subset', ['true', 'false']]],
                'tag' => [['lengthMax', 36]],
              ]
            ]
          ]) ,
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
                'tag' => [['lengthMax', 36]],
                'language' => [['subset', ['ru', 'en']]]
              ]
            ]
          ]),
          'vk' => array_merge_recursive($commonSchema, [
            'v1' => [
              'send' => [
                'txt' => ['required', ['lengthMin', 1], ['lengthMax', 2048]],
                'from' => ['required',['lengthMax', 11], ['lengthMin', 1]],
                'tag' => [['lengthMax', 36]],
                'cascade' => [['subset', ['viber', 'sms', 'voice']]]
              ]
            ]
          ]),
          'pay' => array_merge_recursive($commonSchema, [
            'v1' => [
              'send' => [
                'amount' => [ 'required','numeric', ['min', 1]],
                'tag' => [['lengthMax', 36]],
                'card' => [['lengthMin', 11], ['lengthMax', 14]]
              ],
            ]
          ]),
          'sms' => array_merge_recursive($commonSchema, [
            'v1' => [
              'send' => [
                'txt' => ['required', ['lengthMin', 1], ['lengthMax', 918]],
                'from' => [['lengthMax', 12]],
                'tag' => [['lengthMax', 36]],
                'hidden' => [['lengthMax', 918]]
              ]
            ]
          ]),
          'viber' => array_merge_recursive($commonSchema, [
            'v1' => [
              'send' => [
                'txt' => ['required', ['lengthMin', 1]],
                'from' => [['lengthMax', 12]],
                'cascade' => [['subset', ['sms', 'voice']]],
              ]
            ]
          ]),
          'whatsapp' => array_merge_recursive($commonSchema, [
            'v1' => [
              'send' => [
                'txt' => ['required', ['lengthMin', 1]],
                'file' => [['lengthMax', 256]],
                'tag' => [['lengthMax', 36]],
              ],
              'webook' => [
                'url' => ['required', ['lengthMin', 11], ['lengthMax', 256]]
              ]
            ]
          ]),
          'social' => array_merge_recursive($commonSchema, [
            'v1' => [
              'send' => [
                'txt' => ['required', ['lengthMin', 1]],
                'from' => ['required', ['lengthMax', 12]],
                'tag' => ['alphaNum'],
              ]
            ]
          ]),
        ];

        return $schema;
    }
}
