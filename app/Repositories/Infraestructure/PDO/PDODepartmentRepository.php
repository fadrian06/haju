<?php

namespace App\Repositories\Infraestructure\PDO;

use App\Models\Department;
use App\Repositories\Domain\DepartmentRepository;
use App\Repositories\Exceptions\DuplicatedNamesException;
use PDO;
use PDOException;

class PDODepartmentRepository extends PDORepository implements DepartmentRepository {
  private const FIELDS = 'id, name, registered';
  private const TABLE = 'departments';

  function getAll(): array {
    return $this->ensureIsConnected()
      ->query(sprintf('SELECT %s FROM %s', self::FIELDS, self::TABLE))
      ->fetchAll(PDO::FETCH_FUNC, [self::class, 'mapper']);
  }

  function getById(int $id): ?Department {
    $stmt = $this->ensureIsConnected()
      ->prepare(sprintf('SELECT %s FROM %s WHERE id = ?', self::FIELDS, self::TABLE));

    $stmt->execute([$id]);

    return $stmt->fetchAll(PDO::FETCH_FUNC, [self::class, 'mapper'])[0] ?? null;
  }

  function save(Department $department): void {
    try {
      $query = sprintf('INSERT INTO %s (name) VALUES (?)');

      $this->ensureIsConnected()
        ->prepare($query)
        ->execute([$department->name]);

      $department->setId($this->connection->instance()->lastInsertId());
    } catch (PDOException $exception) {
      if (str_contains($exception, 'UNIQUE constraint failed: departments.name')) {
        throw new DuplicatedNamesException("Department \"{$department->name}\" already exists");
      }

      throw $exception;
    }
  }

  private static function mapper(
    int $id,
    string $name,
    string $registered
  ): Department {
    return (new Department(
      $name,
      self::parseDateTime($registered)
    ))->setId($id);
  }
}
