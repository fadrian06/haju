<?php

namespace App\Repositories\Domain;

use App\Models\Doctor;

/** @extends Repository<Doctor> */
interface DoctorRepository extends Repository {
  function getById(int $id): ?Doctor;
  function getByIdCard(int $idCard): ?Doctor;

  /**
   * @throws DuplicatedNamesException
   * @throws DuplicatedIdCardException
   */
  function save(Doctor $doctor): void;
}
