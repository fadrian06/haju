<?php

declare(strict_types=1);

namespace App\Repositories\Infraestructure\PDO;

use App\Repositories\Exceptions\ConnectionException;
use DateTime;
use PDO;
use PDOException;

abstract class PDORepository {
  private const DATETIME_FORMAT = 'Y-m-d H:i:s';
  protected const DATE_FORMAT = 'Y-m-d';

  function __construct(
    protected readonly Connection $connection,
    protected readonly string $baseUrl
  ) {
  }

  abstract protected static function getTable(): string;

  final function getRowsCount(): int {
    return $this->ensureIsConnected()
      ->query("SELECT count(id) FROM " . static::getTable())
      ->fetchColumn(0);
  }

  /** @throws ConnectionException */
  final protected function ensureIsConnected(): PDO {
    if (!$this->connection) {
      throw new ConnectionException('DB is not connected');
    }

    try {
      $this->connection->instance()->query('SELECT * FROM users');
    } catch (PDOException $exception) {
      if (str_contains($exception->getMessage(), 'no such table: users')) {
        throw new ConnectionException('DB is not installed correctly');
      }

      throw $exception;
    }

    return $this->connection->instance();
  }

  final protected static function parseDateTime(string $raw): DateTime {
    return DateTime::createFromFormat(self::DATETIME_FORMAT, $raw);
  }

  final protected static function getCurrentDatetime(): string {
    return date(self::DATETIME_FORMAT);
  }
}
