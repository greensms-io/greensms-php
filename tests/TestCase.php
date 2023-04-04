<?php

namespace GreenSMS\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase {
  public static function assertObjectHasAttribute(string $attributeName, $object, string $message = ''): void
    {
        self::assertIsObject($object);
        self::assertTrue(property_exists($object, $attributeName));
    }
}