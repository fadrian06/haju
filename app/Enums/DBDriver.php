<?php



namespace HAJU\Enums;

enum DBDriver: string
{
  case MYSQL = 'mysql';
  case SQLITE = 'sqlite';

  public function getPdoDsn(): string
  {
    return match ($this) {
      self::MYSQL => "mysql:host={$_ENV['DB_HOST']}; dbname={$_ENV['DB_DATABASE']}; charset=utf8; port={$_ENV['DB_PORT']}",
      self::SQLITE => "sqlite:{$_ENV['DB_DATABASE']}",
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
