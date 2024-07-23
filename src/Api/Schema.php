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

    private static function getModuleSchema()
    {
        return [['subset', ["ALL", "SMS", "CALL", "VOICE", "VK", "WHATSAPP", "VIBER", "HLR", "PAY"]]];
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
            ],
              'v1.0.1' => [
                  'blacklist' => [
                      'add' => [
                          'to' => $toSchema,
                          'module' => self::getModuleSchema(),
                          'comment' => [['lengthMax', 50]],
                      ],
                      'delete' => [
                          'to' => $toSchema,
                      ],
                  ],
                  'limits' => [
                      'set' => [
                          'type' =>  ['required',['subset', ['IP']]],
                          'value' => ['required',['ipsCommaSeparator']],
                          'module' => self::getModuleSchema(),
                          'comment' => [['lengthMax', 50]],
                      ],
                      'delete' => [
                          'type' =>  ['required',['subset', ['IP']]],
                          'value' => ['required',['ipsCommaSeparator']],
                          'module' => self::getModuleSchema(),
                      ],
                  ],
                  'webhook' => [
                      'set' => [
                          'url' =>  ['required',['url'], ['lengthMax', 1024]],
                          'token' => [['lengthMax', 256]],
                      ],
                  ],
                  'whitelist' => [
                      'set' => [
                          'to' => $toSchema,
                          'module' => self::getModuleSchema(),
                          'comment' => [['lengthMax', 50]],
                      ],
                      'delete' => [
                          'to' => $toSchema,
                      ],
                  ],
              ],
          ],
          'call' => array_merge_recursive($commonSchema, [
            'v1' => [
              'send' => [
                'voice' => [['subset', ['true', 'false']]],
                'tag' => [['lengthMax', 36]],
                'lang' => [['subset', ['ru', 'en']]]
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
                'lang' => [['subset', ['ru', 'en']]]
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
          'pay' => [
            'v1' => [
              'send' => [
                'to' => self::getToSchema(),
                'amount' => [ 'required','numeric', ['min', 1]],
                'tag' => [['lengthMax', 36]],
                'card' => [['lengthMin', 11], ['lengthMax', 14]]
              ],
              'status' => [
                'id' => ['required'],
                'extended' => [['subset', ['true', 'false']]]
              ]
            ]
          ],
          'sms' => array_merge_recursive($commonSchema, [
            'v1' => [
              'send' => [
                'txt' => ['required', ['lengthMin', 1], ['lengthMax', 918]],
                'from' => [['lengthMax', 11]],
                'tag' => [['lengthMax', 36]],
                'hidden' => [['lengthMax', 918]]
              ]
            ]
          ]),
          'viber' => array_merge_recursive($commonSchema, [
            'v1' => [
              'send' => [
                'txt' => ['required', ['lengthMin', 1]],
                'from' => [['lengthMin', 1],['lengthMax', 11]],
                'cascade' => [['subset', ['sms', 'voice']]],
              ]
            ]
          ]),
          'whatsapp' => array_merge_recursive($commonSchema, [
            'v1' => [
              'send' => [
                  'from' => ['required'],
                'txt' => ['required', ['lengthMin', 1],['lengthMax', 1000]],
                'file' => [['lengthMax', 256]],
                'tag' => [['lengthMax', 36]],
              ],
            ]
          ]),
        ];

        return $schema;
    }
}
