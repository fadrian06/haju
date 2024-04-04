<?php

namespace App\Repositories\Domain;

use App\Models\Patient;
use App\Repositories\Exceptions\DuplicatedIdCardException;
use App\Repositories\Exceptions\DuplicatedNamesException;

interface PatientRepository {
  /** @return array<int, Patient> */
  function getAll(): array;
  function getById(int $id): ?Patient;
  function getByIdCard(int $id): ?Patient;

  /**
   * @throws DuplicatedNamesException
   * @throws DuplicatedIdCardException
   */
  function save(Patient $patient): void;
}
