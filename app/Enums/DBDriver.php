<?php

declare(strict_types=1);

namespace HAJU\Enums;

enum DBDriver: string
{
  case MySQL = 'mysql';
  case SQLite = 'sqlite';

  public function getPdoDsn(): string
  {
    return match ($this) {
      self::MySQL => "mysql:host={$_ENV['DB_HOST']}; dbname={$_ENV['DB_DATABASE']}; charset=utf8; port={$_ENV['DB_PORT']}",
      self::SQLite => "sqlite:{$_ENV['DB_DATABASE']}",
    };
  }

  private function getInitDbFile(): string
  {
    $initDbFilePath = DATABASE_PATH . "/init.{$this->value}.sql";

    return file_get_contents($initDbFilePath);
  }

  /**
   * @return string[]
   */
  public function getInitDbQueries(): array
  {
    return explode(';', $this->getInitDbFile());
  }
}
