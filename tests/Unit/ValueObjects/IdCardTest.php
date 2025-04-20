<?php

declare(strict_types=1);

namespace HAJU\Tests\Unit\ValueObjects;

use HAJU\ValueObjects\IdCard;
use InvalidArgumentException;
use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class IdCardTest extends TestCase
{
  #[Test]
  #[DataProvider('validIdCards')]
  public function acceptvalidIdCards(int $idCard): void
  {
    $idCard = new IdCard($idCard);

    self::assertIsInt($idCard->value);
  }

  #[Test]
  #[DataProvider('invalidIdCards')]
  public function throwsExceptionForInvalidIdCards(int $invalidIdCard): void
  {
    self::expectException(InvalidArgumentException::class);

    new IdCard($invalidIdCard);
  }

  public static function validIdCards(): Iterator
  {
    yield [30122782];
    yield [36000001];
  }

  public static function invalidIdCards(): Iterator
  {
    yield [25431];
    yield [1];
    yield [0];
    yield [9];
  }
}
