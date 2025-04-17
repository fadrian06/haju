<?php

declare(strict_types=1);

namespace App\Enums;

enum DBDriver: string {
  case MySQL = 'mysql';
  case SQLite = 'sqlite';

  public function getPdoDsn(): string {
    return match ($this) {
      self::SQLite => "sqlite:{$this->getDatabaseName()}",
      self::MySQL => 'mysql:host='
        . $_ENV['DB_HOST']
        . ';dbname='
        . $this->getDatabaseName()
        . ';port='
        . $_ENV['DB_PORT'],
    };
  }

  public function getDatabaseName(): string {
    return match ($this) {
      self::SQLite => __DIR__
        . '/../../database/'
        . ($_ENV['DB_DATABASE'] ?? 'haju')
        . '.db',
      self::MySQL => $_ENV['DB_DATABASE'],
    };
  }

  public function getInitDatabaseScript(): string {
    $initDbFilePath = $this->getInitDatabaseScriptPath();

    return file_get_contents($initDbFilePath);
  }

  private function getInitDatabaseScriptPath(): string {
    return DATABASE_PATH . "/init.{$_ENV['DB_CONNECTION']->value}.sql";
  }
}
