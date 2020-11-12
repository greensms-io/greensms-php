<?php

namespace GreenSms\Api;

use GreenSms\Api\Schema;

class Modules {

  static function getModules() {
    $schema = Schema::getSchema();

    $modules = [
      'account' => [
        'schema' => $schema['account'],
        'versions' => [
          'v1' => [
            'balance' => [
              'args' => null,
              'method' => 'GET'
            ],
            'token' => [
              'args' => ['params'],
              'method' => 'POST'
            ],
            'tariff' => [
              'args' => null,
              'method' => 'GET'
            ]
          ]
        ]
      ],
      'call' => [
        'schema' => $schema['call'],
        'versions' => [
          'v1' => [
            'send'=> [
              'args'=> ['params'],
              'method'=> 'POST',
            ],
            'status'=> [
              'args'=> ['params'],
              'method'=> 'GET',
            ],
          ]
        ]
          ],
          'whois' => [
            'schema' => $schema['whois'],
            'versions' => [
                'v1' => [
                    'lookup' => [
                        'args' => ['params'],
                        'method' => 'GET'
                    ]
                ]
            ]
        ],
        'general' => [
            'schema' => $schema['general'],
            'static' => true,
            'versions' => [
                'v1' => [
                    'status' => [
                        'args' => null,
                        'method' => 'GET',
                    ],
                ],
            ],
        ],
        'voice' => [
            'schema' => $schema['voice'],
            'versions' => [
                'v1' => [
                    'send' => [
                        'args' => ['params'],
                        'method' => 'POST',
                    ],
                    'status' => [
                        'args' => ['params'],
                        'method' => 'GET',
                    ],
                ]
            ]
        ],
        'pay' => [
            'schema' => $schema['pay'],
            'versions' => [
                'v1' => [
                    'send' => [
                        'args' => ['params'],
                        'method' => 'POST',
                    ],
                    'status' => [
                        'args' => ['params'],
                        'method' => 'GET',
                    ],
                ]
            ]
        ],
        'hlr' => [
            'schema' => $schema['hlr'],
            'versions' => [
                'v1' => [
                    'send' => [
                        'args' => ['params'],
                        'method' => 'POST',
                    ],
                    'status' => [
                        'args' => ['params'],
                        'method' => 'GET',
                    ],
                ]
            ]
        ],
        'sms' => [
            'schema' => $schema['sms'],
            'versions' => [
                'v1' => [
                    'send' => [
                        'args' => ['params'],
                        'method' => 'POST',
                    ],
                    'status' => [
                        'args' => ['params'],
                        'method' => 'GET',
                    ],
                ]
            ]
        ],
        'viber' => [
            'schema' => $schema['viber'],
            'versions' => [
                'v1' => [
                    'send' => [
                        'args' => ['params'],
                        'method' => 'POST',
                    ],
                    'status' => [
                        'args' => ['params'],
                        'method' => 'GET',
                    ],
                ]
            ]
        ],
        'social' => [
            'schema' => $schema['social'],
            'versions' => [
                'v1' => [
                    'send' => [
                        'args' => ['params'],
                        'method' => 'POST',
                    ],
                    'status' => [
                        'args' => ['params'],
                        'method' => 'GET',
                    ],
                ]
            ]
        ],
    ];

    return $modules;
  }
}