<?php

declare(strict_types=1);

namespace App\Enums;

enum DBDriver: string {
  case MySQL = 'mysql';
  case SQLite = 'sqlite';

  public function getPdoDsn(): string {
    return match ($this) {
      self::SQLite => 'sqlite:'
        . __DIR__
        . '/../../database/'
        . ($_ENV['DB_DATABASE'] ?? 'haju')
        . '.db',
      self::MySQL => 'mysql:host='
        . $_ENV['DB_HOST']
        . ';dbname='
        . $_ENV['DB_DATABASE']
        . ';port='
        . $_ENV['DB_PORT'],
    };
  }
}
