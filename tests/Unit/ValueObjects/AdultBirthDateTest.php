<?php

declare(strict_types=1);

namespace HAJU\Tests\Unit\ValueObjects;

use HAJU\ValueObjects\AdultBirthDate;
use HAJU\ValueObjects\Exceptions\InvalidDateException;
use DateInterval;
use DateTimeImmutable;
use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class AdultBirthDateTest extends TestCase
{
  #[Test]
  #[DataProvider('validAdultBirthDates')]
  public function acceptValidAdultBirthDates(string $birthDate): void
  {
    $birthDate = AdultBirthDate::from($birthDate, '-');

    self::assertInstanceOf(AdultBirthDate::class, $birthDate);
  }

  #[Test]
  #[DataProvider('invalidAdultBirthDates')]
  public function throwsExceptionForInvalidAdultBirthDates(
    string $invalidBirthDate,
  ): void {
    self::expectException(InvalidDateException::class);

    AdultBirthDate::from($invalidBirthDate, '-');
  }

  public static function validAdultBirthDates(): Iterator
  {
    $currentDate = new DateTimeImmutable();
    $adultBirthDate = $currentDate->sub(new DateInterval('P18Y'));

    yield $adultBirthDate->format('Y-m-d') => [$adultBirthDate->format('Y-m-d')];
  }

  public static function invalidAdultBirthDates(): Iterator
  {
    $currentDate = new DateTimeImmutable();
    $childBirthDate = $currentDate->sub(new DateInterval('P17Y'));
    $childBirthDate2 = $currentDate->sub(new DateInterval('P1Y'));

    yield $childBirthDate->format('Y-m-d') => [$childBirthDate->format('Y-m-d')];
    yield $childBirthDate2->format('Y-m-d') => [$childBirthDate2->format('Y-m-d')];
  }
}
