<?php

namespace App\Repositories\Infraestructure\PDO;

use App\Models\Department;
use App\Repositories\Domain\DepartmentRepository;
use App\Repositories\Exceptions\DuplicatedNamesException;
use PDO;
use PDOException;

class PDODepartmentRepository extends PDORepository implements DepartmentRepository {
  private const FIELDS = 'id, name, registered, is_active as isActive';
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
      if ($department->getId()) {
        $this->ensureIsConnected()
          ->prepare(sprintf('UPDATE %s SET name = ?, is_active = ? WHERE id = ?', self::TABLE))
          ->execute([$department->name, $department->isActive, $department->getId()]);

        return;
      }

      $query = sprintf(
        'INSERT INTO %s (name, registered, is_active) VALUES (?, ?, ?)',
        self::TABLE
      );

      $date = self::getCurrentDatetime();

      $this->ensureIsConnected()
        ->prepare($query)
        ->execute([$department->name, $date, $department->isActive]);

      $department
        ->setId($this->connection->instance()->lastInsertId())
        ->setRegistered(self::parseDateTime($date));
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
    string $registered,
    bool $isActive
  ): Department {
    $department = new Department(
      $name,
      $isActive
    );

    $department->setId($id)->setRegistered(self::parseDateTime($registered));

    return $department;
  }
}
