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
  function accept_valid_phones(string $phone, string $expected): void {
    $phone = new Phone($phone);

    self::assertSame($phone->__toString(), $expected);
  }

  #[Test]
  #[DataProvider('invalidPhones')]
  function throws_exception_for_invalid_phones(string $invalidPhone): void {
    self::expectException(InvalidPhoneException::class);
    new Phone($invalidPhone);
  }

  static function validPhones(): array {
    return [
      ['04149772694', '+58 414-9772694'],
      ['0414-977-2694', '+58 414-9772694'],
      ['0414 977 2694', '+58 414-9772694'],
      ['+58 414 977 2694', '+58 414-9772694'],
      ['+58-414 977 2694', '+58 414-9772694'],
      ['+58-414-977-2694', '+58 414-9772694'],
      ['+584149772694', '+58 414-9772694'],
    ];
  }

  static function invalidPhones(): array {
    return [
      ['25431'],
    ];
  }
}
