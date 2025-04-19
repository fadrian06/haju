<?php

declare(strict_types=1);

namespace HAJU\Enums;

enum DBDriver: string
{
  case MYSQL = 'mysql';
  case SQLITE = 'sqlite';

  public function getDatabaseName(): string
  {
    return match ($this) {
      self::SQLITE => DATABASE_PATH . "/{$_ENV['DB_DATABASE']}.db",
      self::MYSQL => $_ENV['DB_DATABASE'],
    };
  }
}
