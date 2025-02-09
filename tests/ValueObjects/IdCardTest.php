<?php

use App\ValueObjects\IdCard;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class IdCardTest extends TestCase {
  #[Test]
  #[DataProvider('validIdCards')]
  function acceptvalidIdCards(int $idCard): void {
    $idCard = new IdCard($idCard);

    self::assertIsInt($idCard->value);
  }

  #[Test]
  #[DataProvider('invalidIdCards')]
  function throwsExceptionForInvalidIdCards(int $invalidIdCard): void {
    self::expectException(InvalidArgumentException::class);
    new IdCard($invalidIdCard);
  }

  static function validIdCards(): array {
    return [
      [30122782],
      [36000001],
    ];
  }

  static function invalidIdCards(): array {
    return [
      [25431],
      [1],
      [0],
      [9],
    ];
  }
}
