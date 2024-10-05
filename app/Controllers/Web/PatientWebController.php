<?php

namespace App\Controllers\Web;

use App;
use App\Models\Consultation;
use App\Models\Hospital;
use App\Models\Hospitalization;
use App\Models\Patient;
use App\Repositories\Domain\ConsultationCauseCategoryRepository;
use App\Repositories\Domain\ConsultationCauseRepository;
use App\Repositories\Domain\DepartmentRepository;
use App\Repositories\Domain\DoctorRepository;
use App\Repositories\Domain\PatientRepository;
use App\ValueObjects\ConsultationType;
use App\ValueObjects\Date;
use App\ValueObjects\DepartureStatus;
use App\ValueObjects\Gender;
use DateTimeImmutable;
use Error;
use Throwable;

final class PatientWebController extends Controller {
  private readonly PatientRepository $patientRepository;
  private readonly ConsultationCauseCategoryRepository $consultationCauseCategoryRepository;
  private readonly ConsultationCauseRepository $consultationCauseRepository;
  private readonly DepartmentRepository $departmentRepository;
  private readonly DoctorRepository $doctorRepository;

  function __construct() {
    parent::__construct();

    $this->patientRepository = App::patientRepository();
    $this->consultationCauseCategoryRepository = App::consultationCauseCategoryRepository();
    $this->consultationCauseRepository = App::consultationCauseRepository();
    $this->departmentRepository = App::departmentRepository();
    $this->doctorRepository = App::doctorRepository();
  }

  function showPatients(): void {
    $idCard = App::request()->query['cedula'];

    if ($idCard) {
      $patient = $this->patientRepository->getByIdCard($idCard);

      if ($patient) {
        App::redirect("/pacientes/{$patient->id}");

        return;
      } else {
        parent::setError("Paciente v-$idCard no encontrado");
      }
    }

    App::renderPage('patients/list', 'Pacientes', [
      'patients' => $this->patientRepository->getAll()
    ], 'main');
  }

  function showPatient(int $id): void {
    try {
      $patient = $this->patientRepository->getById($id);

      if (!$patient) {
        throw new Error("Paciente #$id no encontrado");
      }

      $this->patientRepository->setConsultations($patient);

      App::renderPage('patients/info', 'Detalles del paciente', compact('patient'), 'main');
    } catch (Throwable $error) {
      self::setError($error);
      App::redirect('/pacientes');
    }
  }

  function handleRegister(): void {
    try {
      if (!in_array($this->data['gender'], Gender::values())) {
        throw new Error(sprintf('El género es requerido y válido (%s)', join(', ', Gender::values())));
      }

      $patient = new Patient(
        $this->data['first_name'],
        $this->data['second_name'],
        $this->data['first_last_name'],
        $this->data['second_last_name'],
        Date::from($this->data['birth_date'], '-'),
        Gender::from($this->data['gender']),
        $this->data['id_card'],
        $this->loggedUser
      );

      $this->patientRepository->save($patient);
      parent::setMessage('Paciente registrado exitósamente');
    } catch (Throwable $error) {
      parent::setError($error);
    }

    if (App::request()->query['referido']) {
      App::redirect(App::request()->query['referido']);

      return;
    }

    App::redirect('/pacientes');
  }

  function handleEdition(int $id): void {
    try {
      $patient = $this->patientRepository->getById($id);

      if (!$patient?->registeredBy->registeredBy->isEqualTo($this->loggedUser)) {
        throw new Error('Acceso denegado');
      }

      $patient->setFullName($this->data['full_name'])
        ->setIdCard($this->data['id_card']);

      $patient->birthDate = Date::from($this->data['birth_date'], '-');

      $this->patientRepository->save($patient);
      parent::setMessage('Paciente actualizado exitósamente');
    } catch (Throwable $error) {
      parent::setError($error);
    }

    App::redirect('/pacientes');
  }

  function showConsultationRegister(): void {
    $patients = $this->patientRepository->getAll();
    $consultationCauseCategories = $this->consultationCauseCategoryRepository->getAll();
    $doctors = $this->doctorRepository->getAll();

    $departments = [];

    foreach ($this->loggedUser->getDepartment() as $department) {
      if ($department->belongsToExternalConsultation) {
        $departments[] = $department;
      }
    }

    App::renderPage('patients/add-consultation', 'Registrar consulta', compact(
      'patients',
      'consultationCauseCategories',
      'departments',
      'doctors'
    ), 'main');
  }

  function handleConsultationRegister(): void {
    try {
      $patient = $this->patientRepository->getById($this->data['id_card']);

      $consultation = new Consultation(
        $this->data['consultation_type']
          ? ConsultationType::from($this->data['consultation_type'])
          : ConsultationType::FirstTime,
        $this->consultationCauseRepository->getById($this->data['consultation_cause']),
        $this->departmentRepository->getById($this->data['department']),
        $this->doctorRepository->getById($this->data['doctor'])
      );

      $patient->setConsultations($consultation);
      $this->patientRepository->saveConsultationOf($patient);
      parent::setMessage('Consulta registrada exitósamente');
    } catch (Throwable $error) {
      parent::setError($error);
    }

    App::redirect(App::request()->referrer);
  }

  function showHospitalizationRegister(): void {
    $patients = $this->patientRepository->getAll();
    $doctors = $this->doctorRepository->getAll();

    App::renderPage(
      'patients/add-hospitalization',
      'Registrar hospitalización',
      compact('patients', 'doctors'),
      'main'
    );
  }

  function handleHospitalizationRegister(): void {
    try {
      $patient = $this->patientRepository->getById($this->data['id_card']);

      $hospitalization = new Hospitalization(
        $patient,
        $this->doctorRepository->getById($this->data['doctor']),
        $this->data['admission_department'],
        new DateTimeImmutable($this->data['admission_date']),
        $this->data['departure_date']
          ? new DateTimeImmutable($this->data['departure_date'])
          : null,
        $this->data['departure_status']
          ? DepartureStatus::from($this->data['departure_status'])
          : null,
        $this->data['diagnoses'] ?: null
      );

      $patient->setHospitalization($hospitalization);
      $this->patientRepository->saveHospitalizationOf($patient);
      parent::setMessage('Hospitalización registrada exitósamente');
    } catch (Throwable $error) {
      parent::setError($error);
    }

    App::redirect(App::request()->referrer);
  }
}
