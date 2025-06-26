<?php

namespace HAJU\Tests\Unit\ValueObjects;

use HAJU\ValueObjects\Exceptions\InvalidPhoneException;
use HAJU\ValueObjects\Phone;
use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class PhoneTest extends TestCase
{
  #[Test]
  #[DataProvider('validPhones')]
  public function acceptValidPhones(string $phone, string $expected): void
  {
    $phone = new Phone($phone);

    self::assertSame($phone->__toString(), $expected);
  }

  #[Test]
  #[DataProvider('invalidPhones')]
  public function throwsExceptionForInvalidPhones(string $invalidPhone): void
  {
    self::expectException(InvalidPhoneException::class);

    new Phone($invalidPhone);
  }

  public static function validPhones(): Iterator
  {
    yield ['04149772694', '+58 414-9772694'];
    yield ['0414-977-2694', '+58 414-9772694'];
    yield ['0414 977 2694', '+58 414-9772694'];
    yield ['+58 414 977 2694', '+58 414-9772694'];
    yield ['+58-414 977 2694', '+58 414-9772694'];
    yield ['+58-414-977-2694', '+58 414-9772694'];
    yield ['+584149772694', '+58 414-9772694'];
  }

  public static function invalidPhones(): Iterator
  {
    yield ['25431'];
  }
}
