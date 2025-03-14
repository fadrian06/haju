<?php

declare(strict_types=1);

use App\ValueObjects\AdultBirthDate;
use App\ValueObjects\Exceptions\InvalidDateException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class AdultBirthDateTest extends TestCase {
  #[Test]
  #[DataProvider('validAdultBirthDates')]
  function accept_valid_adult_birth_dates(string $birthDate): void {
    $birthDate = AdultBirthDate::from($birthDate, '-');

    self::assertInstanceOf(AdultBirthDate::class, $birthDate);
  }

  #[Test]
  #[DataProvider('invalidAdultBirthDates')]
  function throws_exception_for_invalid_adult_birth_dates(string $invalidBirthDate): void {
    self::expectException(InvalidDateException::class);
    AdultBirthDate::from($invalidBirthDate, '-');
  }

  static function validAdultBirthDates(): array {
    $currentDate = new DateTimeImmutable;
    $adultBirthDate = $currentDate->sub(new DateInterval('P18Y'));

    return [
      $adultBirthDate->format('Y-m-d') => [$adultBirthDate->format('Y-m-d')]
    ];
  }

  static function invalidAdultBirthDates(): array {
    $currentDate = new DateTimeImmutable;
    $childBirthDate = $currentDate->sub(new DateInterval('P17Y'));
    $childBirthDate2 = $currentDate->sub(new DateInterval('P1Y'));

    return [
      $childBirthDate->format('Y-m-d') => [$childBirthDate->format('Y-m-d')],
      $childBirthDate2->format('Y-m-d') => [$childBirthDate2->format('Y-m-d')]
    ];
  }
}
