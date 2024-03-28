<?php

namespace App\Repositories\Infraestructure\PDO;

use App\Repositories\Exceptions\ConnectionException;
use DateTime;
use PDO;
use PDOException;

abstract class PDORepository {
  private const DATETIME_FORMAT = 'Y-m-d H:i:s';

  function __construct(
    protected readonly Connection $connection,
    protected readonly string $baseUrl
  ) {
  }

  /** @throws ConnectionException */
  protected function ensureIsConnected(): PDO {
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

  protected static function parseDateTime(string $raw): DateTime {
    return DateTime::createFromFormat(self::DATETIME_FORMAT, $raw);
  }

  protected static function getCurrentDatetime(): string {
    return date(self::DATETIME_FORMAT);
  }
}
