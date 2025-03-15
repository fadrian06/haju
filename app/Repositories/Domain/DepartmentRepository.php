<?php

declare(strict_types=1);

namespace App\Repositories\Domain;

use App\Models\Department;
use App\Repositories\Exceptions\DuplicatedNamesException;

/** @extends Repository<Department> */
interface DepartmentRepository extends Repository {
  public function getById(int $id): ?Department;

  /** @throws DuplicatedNamesException */
  public function save(Department $department): void;
}
