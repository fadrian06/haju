<?php

declare(strict_types=1);

use App\ValueObjects\Exceptions\InvalidPhoneException;
use App\ValueObjects\Phone;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class PhoneTest extends TestCase {
  #[Test]
  #[DataProvider('validPhones')]
  public function accept_valid_phones(string $phone, string $expected): void {
    $phone = new Phone($phone);

    self::assertSame($phone->__toString(), $expected);
  }

  #[Test]
  #[DataProvider('invalidPhones')]
  public function throws_exception_for_invalid_phones(string $invalidPhone): void {
    self::expectException(InvalidPhoneException::class);

    new Phone($invalidPhone);
  }

  public static function validPhones(): Iterator {
    yield ['04149772694', '+58 414-9772694'];
    yield ['0414-977-2694', '+58 414-9772694'];
    yield ['0414 977 2694', '+58 414-9772694'];
    yield ['+58 414 977 2694', '+58 414-9772694'];
    yield ['+58-414 977 2694', '+58 414-9772694'];
    yield ['+58-414-977-2694', '+58 414-9772694'];
    yield ['+584149772694', '+58 414-9772694'];
  }

  public static function invalidPhones(): Iterator {
    yield ['25431'];
  }
}
