<?php

namespace App\Repositories\Infraestructure\PDO;

use App\Models\Date;
use App\Models\Gender;
use App\Models\Phone;
use App\Models\ProfessionPrefix;
use App\Models\Role;
use App\Models\User;
use App\Repositories\Domain\UserRepository;
use App\Repositories\Exceptions\DuplicatedAvatarsException;
use App\Repositories\Exceptions\DuplicatedEmailsException;
use App\Repositories\Exceptions\DuplicatedIdCardException;
use App\Repositories\Exceptions\DuplicatedNamesException;
use App\Repositories\Exceptions\DuplicatedPhonesException;
use PDO;
use PDOException;
use PharIo\Manifest\Email;
use PharIo\Manifest\Url;

class PDOUserRepository extends PDORepository implements UserRepository {
  private const FIELDS = <<<SQL_FIELDS
  id, first_name as firstName, last_name as lastName,
  birth_date as birthDateTimestamp, gender, role, prefix, id_card as idCard,
  password, phone, email, address, avatar, registered
  SQL_FIELDS;

  private const TABLE = 'users';

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
            first_name, last_name, birth_date, gender, role, prefix, id_card,
            password, phone, email, address, avatar
          ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        SQL,
        self::TABLE
      );

      $this->ensureIsConnected()
        ->prepare($query)
        ->execute([
          $user->firstName,
          $user->lastName,
          $user->birthDate->timestamp,
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

  private static function mapper(
    int $id,
    string $firstName,
    string $lastName,
    int $birthDateTimestamp,
    string $gender,
    string $role,
    ?string $prefix,
    int $idCard,
    string $password,
    ?string $phone,
    ?string $email,
    ?string $address,
    ?string $avatar,
    ?string $registered
  ): User {
    return (new User(
      $firstName,
      $lastName,
      Date::fromTimestamp($birthDateTimestamp),
      Gender::from($gender),
      Role::from($role),
      ProfessionPrefix::tryFrom($prefix ?? ''),
      $idCard,
      $password,
      $phone ? new Phone($phone) : null,
      $email ? new Email($email) : null,
      $address ?: null,
      $avatar ? new Url($avatar) : null,
      self::parseDateTime($registered)
    ))->setId($id);
  }
}
