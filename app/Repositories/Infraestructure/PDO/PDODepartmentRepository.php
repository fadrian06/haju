<?php



namespace HAJU\Repositories\Infraestructure\PDO;

use HAJU\Models\Department;
use HAJU\Repositories\Domain\DepartmentRepository;
use HAJU\Repositories\Exceptions\DuplicatedNamesException;
use PDO;
use PDOException;
use PharIo\Manifest\Url;

final class PDODepartmentRepository extends PDORepository implements DepartmentRepository
{
  private const FIELDS = <<<sql
    id, name, registered_date as registeredDateTime,
    belongs_to_external_consultation as belongsToExternalConsultation,
    icon_file_path as iconFilePath, is_active as isActive
  sql;

  protected static function getTable(): string
  {
    return 'departments';
  }

  public function getAll(): array
  {
    return $this->ensureIsConnected()
      ->query(sprintf('SELECT %s FROM %s ORDER BY name', self::FIELDS, self::getTable()))
      ->fetchAll(PDO::FETCH_FUNC, [self::class, 'mapper']);
  }

  public function getById(int $id): ?Department
  {
    $stmt = $this->ensureIsConnected()
      ->prepare(sprintf('SELECT %s FROM %s WHERE id = ?', self::FIELDS, self::getTable()));

    $stmt->execute([$id]);

    return $stmt->fetchAll(PDO::FETCH_FUNC, [self::class, 'mapper'])[0] ?? null;
  }

  public function save(Department $department): void
  {
    try {
      if ($department->id) {
        $this->ensureIsConnected()
          ->prepare(sprintf('UPDATE %s SET name = ?, is_active = ? WHERE id = ?', self::getTable()))
          ->execute([$department->name, $department->isActive(), $department->id]);

        return;
      }

      $query = sprintf(
        'INSERT INTO %s (name, registered_date, icon_file_path, belongs_to_external_consultation, is_active)
        VALUES (?, ?, ?, ?, ?)',
        self::getTable()
      );

      $date = self::getCurrentDatetime();

      $this->ensureIsConnected()
        ->prepare($query)
        ->execute([
          $department->name,
          $date,
          $department->iconFilePath,
          $department->belongsToExternalConsultation,
          $department->isActive()
        ]);

      $department
        ->setId((int) $this->pdo->lastInsertId())
        ->setRegisteredDate(self::parseDateTime($date));
    } catch (PDOException $exception) {
      if (str_contains($exception->getMessage(), 'UNIQUE constraint failed: departments.name')) {
        throw new DuplicatedNamesException("Departamento \"{$department->name}\" ya existe");
      }

      throw $exception;
    }
  }

  public function mapper(
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
