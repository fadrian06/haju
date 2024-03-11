<?php

namespace App\Models;

enum Role: string {
  case Director = 'Director/a';
  case Coordinator = 'Coordinador/a';
  case Secretary = 'Secretario/a';

  function getParsed(Gender $gender): string {
    return sprintf(
      '%s%s',
      substr($this->value, 0, strlen($this->value) - 2),
      $gender === Gender::Female ? 'a' : ''
    );
  }

  function isHigherThan(self $role): bool {
    return $this->getLevel() >= $role->getLevel();
  }

  /** @return array<int, self> */
  static function getLowersThan(self $role): array {
    return array_filter(self::cases(), function (self $case) use ($role): bool {
      return $case->getLevel() < $role->getLevel();
    });
  }

  function getLevel(): int {
    return match ($this) {
      self::Director => 3,
      self::Coordinator => 2,
      self::Secretary => 1
    };
  }
}
