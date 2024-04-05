<?php

namespace App\Repositories\Domain;

use App\Models\Department;
use App\Repositories\Exceptions\DuplicatedNamesException;

/** @extends Repository<Department> */
interface DepartmentRepository extends Repository {
  function getById(int $id): ?Department;

  /** @throws DuplicatedNamesException */
  function save(Department $department): void;
}
