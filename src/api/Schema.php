<?php

namespace GreenSms\Api;

class Schema
{
    private static function getToSchema()
    {
        $toSchema = ['required',['lengthMin', 11], ['lengthMax', 14], ['regex', '^[0-9]+']];
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
          'extended' => ['boolean', 'required']
        ]
      ]
    ];

        return $commonSchema;
    }

    public static function getSchema()
    {
        $commonSchema = self::getCommonSchema();

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
      'voice' => [
        'v1' => [
          'send' => [
            'txt' => ['required', ['lengthMin', 1], ['lengthMax', 5], ['regex', '^[0-9]+']],
            'lang' => [['subset', ['ru', 'en']]]
          ]
        ]
          ],
          'pay' => [
            'v1' => [
              'send' => [
                'amount' => [ 'required','integer', ['min', 1]],
                'tag' => ['alphaNum']
              ],

            ]
            ],
            'sms' => [
              'v1' => [
                'send' => [
                  'txt' => ['required', ['lengthMin', 1]],
                  'from' => ['alphaNum'],
                  'tag' => ['alphaNum'],
                ]
              ]
                ],
                'viber' => [
                  'v1' => [
                    'send' => [
                      'txt' => ['required', ['lengthMin', 1]],
                      'from' => ['alphaNum'],
                      'cascase' => [['subset', ['sms', 'voice']]],
                    ]
                  ]
                    ],
                    'social' => [
                      'v1' => [
                        'send' => [
                          'txt' => ['required', ['lengthMin', 1]],
                          'from' => ['alphaNum'],
                          'tag' => ['alphaNum'],
                        ]
                      ]
                        ],
    ],

    ];
    }
}
