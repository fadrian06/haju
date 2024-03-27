<?php

namespace App\Repositories\Infraestructure\PDO;

use App\Models\Department;
use App\Repositories\Domain\DepartmentRepository;
use App\Repositories\Exceptions\DuplicatedNamesException;
use PDO;
use PDOException;
use PharIo\Manifest\Url;

class PDODepartmentRepository extends PDORepository implements DepartmentRepository {
  private const FIELDS = <<<SQL_FIELDS
    id, name, registered_date as registeredDateTime,
    belongs_to_external_consultation as belongsToExternalConsultation,
    icon_file_path as iconFilePath, is_active as isActive
  SQL_FIELDS;
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
          ->execute([$department->getName(), $department->getActiveStatus(), $department->getId()]);

        return;
      }

      $query = sprintf(
        'INSERT INTO %s (name, registered_date, icon_file_path, belongs_to_external_consultation, is_active)
        VALUES (?, ?, ?, ?, ?)',
        self::TABLE
      );

      $date = self::getCurrentDatetime();

      $this->ensureIsConnected()
        ->prepare($query)
        ->execute([
          $department->getName(),
          $date,
          $department->iconFilePath,
          $department->belongsToExternalConsultation,
          $department->getActiveStatus()
        ]);

      $department
        ->setId($this->connection->instance()->lastInsertId())
        ->setRegisteredDate(self::parseDateTime($date));
    } catch (PDOException $exception) {
      if (str_contains($exception, 'UNIQUE constraint failed: departments.name')) {
        throw new DuplicatedNamesException("Departamento \"{$department->getName()}\" ya existe");
      }

      throw $exception;
    }
  }

  function mapper(
    int $id,
    string $name,
    string $registeredDateTime,
    bool $belongsToExternalConsultation,
    string $iconFilePath,
    bool $isActive
  ): Department {
    $department = new Department(
      $name,
      new Url("{$this->baseUrl}/" . urlencode($iconFilePath)),
      $belongsToExternalConsultation,
      $isActive
    );

    $department->setId($id)->setRegisteredDate(self::parseDateTime($registeredDateTime));

    return $department;
  }
}
