<?php

namespace GreenSms\Api;

class Schema {

  private static function getToSchema() {
    $toSchema = ['required',['lengthMin', 11], ['lengthMax', 14], ['regex', '^[0-9]+']];
    return $toSchema;
  }

  private static function getIdSchema() {
    $toSchema = ['required',['lengthMin', 36], ['lengthMax', 36]];
    return $toSchema;
  }

  private static function getCommonSchema() {
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
  }

  static function getSchema() {
    $schema = [
      'account' => [
        'v1' => [
          'token' => [
            'expire' => ['integer', ['min', 0]],
            ''
          ]
        ]
      ]
    ];
  }
}