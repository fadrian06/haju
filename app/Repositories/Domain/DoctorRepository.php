<?php

declare(strict_types=1);

namespace App\Repositories\Domain;

use App\Models\Doctor;

/** @extends Repository<Doctor> */
interface DoctorRepository extends Repository {
  public function getByIdCard(int $idCard): ?Doctor;

  /**
   * @throws DuplicatedNamesException
   * @throws DuplicatedIdCardException
   */
  public function save(Doctor $doctor): void;
}
