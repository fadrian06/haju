<?php

namespace App\Repositories\Infraestructure\PDO;

use App\Repositories\Exceptions\ConnectionException;
use DateTime;
use PDO;
use PDOException;

abstract class PDORepository {
  protected ?Connection $connection = null;

  function setConnection(Connection $connection): void {
    $this->connection = $connection;
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
    return DateTime::createFromFormat('Y-m-d H:i:s', $raw);
  }
}
