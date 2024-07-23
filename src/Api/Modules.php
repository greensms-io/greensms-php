<?php

namespace GreenSMS\Api;

class Modules
{
    public static function getModules(): array
    {
        $schema = Schema::getSchema();

        return [
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
                ],
              ],
                  'v1.0.1' => [
                      'blacklist' => [
                          'get' => [
                              'args' => null,
                              'method' => 'GET',
                          ],
                          'add' => [
                              'args' => ['params'],
                              'method' => 'POST',
                          ],
                          'delete' => [
                              'args' => ['params'],
                              'method' => 'DELETE',
                          ],
                      ],
                      'limits' => [
                          'get' => [
                              'args' => null,
                              'method' => 'GET',
                          ],
                          'set' => [
                              'args' => null,
                              'method' => 'POST',
                          ],
                          'delete' => [
                              'args' => null,
                              'method' => 'DELETE',
                          ],
                      ],
                      'webhook' => [
                          'get' => [
                              'args' => null,
                              'method' => 'GET',
                          ],
                          'set' => [
                              'args' => null,
                              'method' => 'POST',
                          ],
                          'delete' => [
                              'args' => null,
                              'method' => 'DELETE',
                          ],
                      ],
                      'whitelist' => [
                          'get' => [
                              'args' => null,
                              'method' => 'GET',
                          ],
                          'add' => [
                              'args' => null,
                              'method' => 'POST',
                          ],
                          'delete' => [
                              'args' => null,
                              'method' => 'DELETE',
                          ],
                      ],
                  ],
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
                'receive'=> [
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
          'vk' => [
            'schema' => $schema['vk'],
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
          'whatsapp' => [
            'schema' => $schema['vk'],
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
        ];
    }
}
