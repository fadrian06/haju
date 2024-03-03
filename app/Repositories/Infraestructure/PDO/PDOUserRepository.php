<?php

namespace App\Repositories\Infraestructure\PDO;

use App\Models\Gender;
use App\Models\Phone;
use App\Models\ProfessionPrefix;
use App\Models\Role;
use App\Models\User;
use App\Repositories\Domain\UserRepository;
use App\Repositories\Exceptions\ConnectionException;
use App\Repositories\Exceptions\DuplicatedAvatarsException;
use App\Repositories\Exceptions\DuplicatedEmailsException;
use App\Repositories\Exceptions\DuplicatedIdCardException;
use App\Repositories\Exceptions\DuplicatedNamesException;
use App\Repositories\Exceptions\DuplicatedPhonesException;
use PDO;
use PDOException;
use PharIo\Manifest\Email;
use PharIo\Manifest\Url;

class PDOUserRepository implements UserRepository {
  private ?Connection $connection = null;
  private const FIELDS = <<<SQL_FIELDS
  id, first_name as firstName, last_name as lastName, gender, role, prefix,
  id_card as idCard, password, phone, email, address, avatar
  SQL_FIELDS;

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
          INSERT INTO %s (
            first_name, last_name, gender, role, prefix, id_card, password,
            phone, email, address, avatar
          ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        SQL,
        self::TABLE
      );

      $this->ensureIsConnected()
        ->prepare($query)
        ->execute([
          $user->firstName,
          $user->lastName,
          $user->gender->value,
          $user->role->value,
          $user->prefix?->value,
          $user->idCard,
          $user->getPassword(),
          $user->phone,
          $user->email?->asString(),
          $user->address,
          $user->avatar?->asString()
        ]);

      $user->setId($this->connection->instance()->lastInsertId());
    } catch (PDOException $exception) {
      if (str_contains($exception, 'UNIQUE constraint failed: users.id_card')) {
        throw new DuplicatedIdCardException("ID card \"{$user->idCard}\" already exists");
      }

      if (str_contains($exception, 'UNIQUE constraint failed: users.first_name, users.last_name')) {
        throw new DuplicatedNamesException("User \"{$user->getFullName()}\" already exists");
      }

      if (str_contains($exception, 'UNIQUE constraint failed: users.phone')) {
        throw new DuplicatedPhonesException("Phone \"{$user->phone}\" already exists");
      }

      if (str_contains($exception, 'UNIQUE constraint failed: users.email')) {
        throw new DuplicatedEmailsException("Email \"{$user->email->asString()}\" already exists");
      }

      if (str_contains($exception, 'UNIQUE constraint failed: users.avatar')) {
        throw new DuplicatedAvatarsException("Avatar \"{$user->avatar->asString()}\" already exists");
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
    string $gender,
    string $role,
    ?string $prefix,
    int $idCard,
    string $password,
    ?string $phone,
    ?string $email,
    ?string $address,
    ?string $avatar
  ): User {
    return (new User(
      $firstName,
      $lastName,
      Gender::from($gender),
      Role::from($role),
      ProfessionPrefix::tryFrom($prefix ?? ''),
      $idCard,
      $password,
      $phone ? new Phone($phone) : null,
      $email ? new Email($email) : null,
      $address ?: null,
      $avatar ? new Url($avatar) : null
    ))->setId($id);
  }
}
