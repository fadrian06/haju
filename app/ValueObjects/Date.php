<?php

declare(strict_types=1);

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
  final public function __construct(int $day, int $month, int $year) {
    $this->validate($day, $month, $year);
    $date = DateTime::createFromFormat(self::FORMAT, "{$day}-{$month}-{$year}");
    $this->timestamp = $date->getTimestamp();
    $this->day = (int) $date->format('d');
    $this->month = (int) $date->format('m');
    $this->year = (int) $date->format('Y');
  }

  protected function validate(int $day, int $month, int $year): void {
    switch (true) {
      case $month < 1:
      case $month > 12:
      case $year < 1900:
      case $year > (date('Y') - 18):
      case $day < 1:
        // TODO: check if $month has 30 or 31 days
        break;
      case $day > 31:
        // TODO: check if $year if february has 28 or 29 days
        break;
      case $month === 2 && $day > 29:
        throw new InvalidDateException("Invalid date \"{$day}/{$month}/{$year}\"");
    }
  }

  public function getWithDashes(): string {
    return date('Y-m-d', $this->timestamp);
  }

  /** @throws InvalidDateException */
  public static function from(string $raw, string $separator): static {
    $regexp = "/^(?<year>\d{4}){$separator}(?<month>\d{2}){$separator}(?<day>\d{2})$/";

    if (preg_match($regexp, $raw, $matches)) {
      return new static(
        (int) $matches['day'],
        (int) $matches['month'],
        (int) $matches['year']
      );
    }

    throw new InvalidDateException("Fecha inválida \"{$raw}\"");
  }

  public static function fromTimestamp(int $timestamp): static {
    [$day, $month, $year] = explode('-', date(self::FORMAT, $timestamp));

    return new static((int) $day, (int) $month, (int) $year);
  }

  public function __toString(): string {
    return "{$this->day}/{$this->month}/{$this->year}";
  }
}
