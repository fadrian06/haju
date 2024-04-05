<?php

namespace App\ValueObjects;

use App\ValueObjects\Exceptions\InvalidDateException;
use DateTime;
use Stringable;

readonly class Date implements Stringable {
  private const FORMAT = 'd-m-Y';

  public int $day;
  public int $month;
  public int $year;
  public int $timestamp;

  /** @throws InvalidDateException */
  function __construct(int $day, int $month, int $year) {
    switch (true) {
      case $month < 1:
      case $month > 12:
      case $year < 1900:
      case $year > (date('Y') - 18):
      case $day < 1:
      // TODO: check if $month has 30 or 31 days
      case $day > 31:
      // TODO: check if $year if february has 28 or 29 days
      case $month === 2 && $day > 29:
        throw new InvalidDateException("Invalid date \"$day/$month/$year\"");
    }

    $date = DateTime::createFromFormat(self::FORMAT, "{$day}-{$month}-{$year}");
    $this->timestamp = $date->getTimestamp();
    $this->day = (int) $date->format('d');
    $this->month = (int) $date->format('m');
    $this->year = (int) $date->format('Y');
  }

  function getWithDashes(): string {
    return date('Y-m-d', $this->timestamp);
  }

  /** @throws InvalidDateException */
  static function from(string $raw, string $separator): self {
    $regexp = "/^(?<year>\d{4})$separator(?<month>\d{2})$separator(?<day>\d{2})$/";

    if (preg_match($regexp, $raw, $matches)) {
      return new self($matches['day'], $matches['month'], $matches['year']);
    }

    throw new InvalidDateException("Fecha inválida \"$raw\"");
  }

  static function fromTimestamp(int $timestamp): self {
    [$day, $month, $year] = explode('-', date(self::FORMAT, $timestamp));

    return new self($day, $month, $year);
  }

  function __toString(): string {
    return "{$this->day}/{$this->month}/{$this->year}";
  }
}
