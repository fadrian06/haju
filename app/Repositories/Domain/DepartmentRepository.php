<?php

namespace App\Repositories\Domain;

use App\Models\Department;
use App\Repositories\Exceptions\DuplicatedNamesException;

interface DepartmentRepository {
  /** @return array<int, Department> */
  function getAll(): array;
  function getById(int $id): ?Department;

  /** @throws DuplicatedNamesException */
  function save(Department $department): void;
}
