<?php

namespace App\Repositories\Domain;

use App\Models\Patient;
use App\Repositories\Exceptions\DuplicatedIdCardException;
use App\Repositories\Exceptions\DuplicatedNamesException;

/** @extends Repository<Patient> */
interface PatientRepository extends Repository {
  function getById(int $id): ?Patient;
  function getByIdCard(int $id): ?Patient;

  /**
   * @throws DuplicatedNamesException
   * @throws DuplicatedIdCardException
   */
  function save(Patient $patient): void;
  function saveConsultationOf(Patient $patient): void;
  function saveHospitalizationOf(Patient $patient): void;
  function setConsultationsById(Patient $patient, int $causeId): void;
  function setConsultations(Patient $patient): void;
  function setHospitalizations(Patient $patient): void;
  function getConsultationsCount(): int;
}
