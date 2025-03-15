<?php

declare(strict_types=1);

namespace App\ValueObjects;

enum Appointment: string {
  case Director = 'Director/a';
  case Coordinator = 'Coordinador/a';
  case Secretary = 'Secretario/a';

  public function getId(): int {
    return match ($this) {
      self::Director => 1,
      self::Coordinator => 2,
      self::Secretary => 3
    };
  }

  public function isDirector(): bool {
    return $this === self::Director;
  }

  public function getParsed(Gender $gender): string {
    $step1 = str_replace($gender === Gender::Male ? '/a' : '/', '', $this->value);

    return str_replace('oa', 'a', $step1);
  }

  public function isHigherThan(self $role): bool {
    return $this->getLevel() >= $role->getLevel();
  }

  public function isLowerOrEqualThan(self $appointment): bool {
    return $this->getLevel() <= $appointment->getLevel();
  }

  /** @return array<int, self> */
  public static function getLowersThan(self $role): array {
    return array_filter(self::cases(), fn(self $case): bool => $case->getLevel() < $role->getLevel());
  }

  public function getLevel(): int {
    return match ($this) {
      self::Director => 3,
      self::Coordinator => 2,
      self::Secretary => 1
    };
  }
}
