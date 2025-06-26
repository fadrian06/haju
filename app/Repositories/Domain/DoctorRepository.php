<?php



namespace HAJU\Repositories\Domain;

use HAJU\Models\Doctor;

/** @extends Repository<Doctor> */
interface DoctorRepository extends Repository
{
  public function getByIdCard(int $idCard): ?Doctor;

  /**
   * @throws DuplicatedNamesException
   * @throws DuplicatedIdCardException
   */
  public function save(Doctor $doctor): void;
}
