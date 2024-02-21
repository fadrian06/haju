<?php

namespace App\Repositories\Infraestructure\PDO;

use App\Models\User;
use App\Repositories\Domain\UserRepository;
use App\Repositories\Exceptions\ConnectionException;
use App\Repositories\Exceptions\DuplicatedIdCardException;
use PDO;
use PDOException;

class PDOUserRepository implements UserRepository {
  private ?Connection $connection = null;

  function setConnection(Connection $connection): void {
    $this->connection = $connection;
  }

  function getAll(): array {
    return $this->ensureIsConnected()
      ->query('SELECT id, id_card as idCard, password FROM users')
      ->fetchAll(PDO::FETCH_FUNC, [self::class, 'mapper']);
  }

  function getByIdCard(int $idCard): ?User {
    $stmt = $this->ensureIsConnected()
      ->prepare('SELECT id, id_card as idCard, password FROM users WHERE id_card = ?');

    $stmt->execute([$idCard]);

    return $stmt->fetchAll(PDO::FETCH_FUNC, [self::class, 'mapper'])[0] ?? null;
  }

  function getById(int $id): ?User {
    $stmt = $this->ensureIsConnected()
      ->prepare('SELECT id, id_card as idCard, password FROM users WHERE id = ?');

    $stmt->execute([$id]);

    return $stmt->fetchAll(PDO::FETCH_FUNC, [self::class, 'mapper'])[0] ?? null;
  }

  function save(User $user): void {
    if ($user->getId()) {
      $this->ensureIsConnected()
        ->prepare('UPDATE users SET password = ? WHERE id = ?')
        ->execute([$user->getPassword(), $user->getId()]);

      return;
    }

    try {
      $this->ensureIsConnected()
        ->prepare('INSERT INTO users (id_card, password) VALUES (?, ?)')
        ->execute([$user->idCard, $user->getPassword()]);

      $user->setId($this->connection->instance()->lastInsertId());
    } catch (PDOException $exception) {
      if (str_contains($exception, 'UNIQUE constraint failed: users.id_card')) {
        throw new DuplicatedIdCardException("ID card \"{$user->idCard}\" already exists");
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

  private static function mapper(int $id, int $idCard, string $password): User {
    return (new User($idCard, $password))->setId($id);
  }
}
