<?php

declare(strict_types=1);

namespace HAJU\Repositories\Infraestructure\PDO;

use HAJU\Repositories\Exceptions\RepositoryException;
use DateTimeImmutable;
use DateTimeInterface;
use PDO;
use PDOException;

abstract class PDORepository
{
  private const DATETIME_FORMAT = 'Y-m-d H:i:s';
  protected const DATE_FORMAT = 'Y-m-d';

  public function __construct(
    protected readonly PDO $pdo,
    protected readonly string $baseUrl,
  ) {
  }

  abstract protected static function getTable(): string;

  final public function getRowsCount(): int
  {
    return $this->ensureIsConnected()
      ->query("SELECT count(id) FROM " . static::getTable())
      ->fetchColumn(0);
  }

  /** @throws RepositoryException */
  final protected function ensureIsConnected(): PDO
  {
    if (!$this->pdo) {
      throw new RepositoryException('DB is not connected');
    }

    try {
      $this->pdo->query('SELECT * FROM users');
    } catch (PDOException $exception) {
      if (str_contains($exception->getMessage(), 'no such table: users')) {
        throw new RepositoryException('DB is not installed correctly');
      }

      throw $exception;
    }

    return $this->pdo;
  }

  final protected static function parseDateTime(string $raw): DateTimeInterface
  {
    return DateTimeImmutable::createFromFormat(self::DATETIME_FORMAT, $raw);
  }

  final protected static function getCurrentDatetime(): string
  {
    return date(self::DATETIME_FORMAT);
  }
}
