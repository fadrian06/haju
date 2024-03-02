<?php

namespace App\Repositories\Infraestructure\PDO;

use App\Models\GenrePrefix;
use App\Models\User;
use App\Repositories\Domain\UserRepository;
use App\Repositories\Exceptions\ConnectionException;
use App\Repositories\Exceptions\DuplicatedIdCardException;
use App\Repositories\Exceptions\DuplicatedNamesException;
use PDO;
use PDOException;

class PDOUserRepository implements UserRepository {
  private ?Connection $connection = null;
  private const FIELDS = 'id, first_name as firstName, last_name as lastName, speciality, prefix, id_card as idCard, password, avatar';
  private const TABLE = 'users';

  function setConnection(Connection $connection): void {
    $this->connection = $connection;
  }

  function getAll(): array {
    return $this->ensureIsConnected()
      ->query(sprintf('SELECT %s FROM %s', self::FIELDS, self::TABLE))
      ->fetchAll(PDO::FETCH_FUNC, [self::class, 'mapper']);
  }

  function getByIdCard(int $idCard): ?User {
    $stmt = $this->ensureIsConnected()
      ->prepare(sprintf('SELECT %s FROM %s WHERE id_card = ?', self::FIELDS, self::TABLE));

    $stmt->execute([$idCard]);

    return $stmt->fetchAll(PDO::FETCH_FUNC, [self::class, 'mapper'])[0] ?? null;
  }

  function getById(int $id): ?User {
    $stmt = $this->ensureIsConnected()
      ->prepare(sprintf('SELECT %s FROM %s WHERE id = ?', self::FIELDS, self::TABLE));

    $stmt->execute([$id]);

    return $stmt->fetchAll(PDO::FETCH_FUNC, [self::class, 'mapper'])[0] ?? null;
  }

  function save(User $user): void {
    if ($user->getId()) {
      $this->ensureIsConnected()
        ->prepare(sprintf('UPDATE %s SET password = ? WHERE id = ?', self::TABLE))
        ->execute([$user->getPassword(), $user->getId()]);

      return;
    }

    try {
      $query = sprintf(
        <<<SQL
          INSERT INTO %s (first_name, last_name, speciality, prefix, id_card, password, avatar)
          VALUES (?, ?, ?, ?, ?, ?, ?)
        SQL,
        self::TABLE
      );

      $this->ensureIsConnected()
        ->prepare($query)
        ->execute([
          $user->firstName,
          $user->lastName,
          $user->speciality,
          $user->prefix?->value,
          $user->idCard, $user->getPassword(),
          $user->avatar
        ]);

      $user->setId($this->connection->instance()->lastInsertId());
    } catch (PDOException $exception) {
      if (str_contains($exception, 'UNIQUE constraint failed: users.id_card')) {
        throw new DuplicatedIdCardException("ID card \"{$user->idCard}\" already exists");
      }

      if (str_contains($exception, 'UNIQUE constraint failed: users.first_name, users.last_name')) {
        throw new DuplicatedNamesException("User \"{$user->getFullName()}\" already exists");
      }

      throw $exception;
    }
  }

  /** @throws ConnectionException */
  private function ensureIsConnected(): PDO {
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

  private static function mapper(
    int $id,
    string $firstName,
    string $lastName,
    string $speciality,
    ?string $prefix,
    int $idCard,
    string $password,
    ?string $avatar
  ): User {
    return (new User(
      $firstName,
      $lastName,
      $speciality,
      GenrePrefix::tryFrom($prefix ?? ''),
      $idCard,
      $password,
      $avatar ?: null
    ))->setId($id);
  }
}
