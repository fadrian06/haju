<?php

declare(strict_types=1);

namespace App\Repositories\Domain;

use App\OldModels\Patient;
use App\Repositories\Exceptions\DuplicatedIdCardException;
use App\Repositories\Exceptions\DuplicatedNamesException;

/**
 * @extends Repository<Patient>
 */
interface PatientRepository extends Repository {
  public function getByIdCard(int $id): ?Patient;
  public function getByHospitalizationId(int $id): ?Patient;

  /**
   * @throws DuplicatedNamesException
   * @throws DuplicatedIdCardException
   */
  public function save(Patient $patient): void;

  public function saveConsultationOf(Patient $patient): void;
  public function saveHospitalizationOf(Patient $patient): void;
  public function setConsultationsById(Patient $patient, int $causeId): void;
  public function setConsultations(Patient $patient): void;
  public function setHospitalizations(Patient $patient): void;
  public function getConsultationsCount(): int;
  public function withHospitalizations(): self;
  public function withConsultations(): self;
}
