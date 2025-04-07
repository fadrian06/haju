<?php

declare(strict_types=1);

namespace App\Repositories\Domain;

use App\Models\Department;
use App\Repositories\Exceptions\DuplicatedNamesException;

/** @extends Repository<Department> */
interface DepartmentRepository extends Repository {
  /** @throws DuplicatedNamesException */
  public function save(Department $department): void;

  public function mapper(
    int $id,
    string $name,
    string $registeredDateTime,
    bool $belongsToExternalConsultation,
    string $iconFilePath,
    bool $isActive
  ): Department;
}
