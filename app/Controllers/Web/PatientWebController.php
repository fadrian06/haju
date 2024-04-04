<?php

namespace App\Controllers\Web;

use App;
use App\Models\Patient;
use App\Repositories\Domain\PatientRepository;
use App\ValueObjects\Date;
use App\ValueObjects\Gender;
use Error;
use Throwable;

final class PatientWebController extends Controller {
  private readonly PatientRepository $repository;

  function __construct() {
    parent::__construct();

    $this->repository = App::patientRepository();
  }

  function showPatients(): void {
    App::renderPage('patients', 'Pacientes', [
      'patients' => $this->repository->getAll()
    ], 'main');
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

      $this->repository->save($patient);
      parent::setMessage('Paciente registrado exitósamente');
    } catch (Throwable $error) {
      parent::setError($error);
    }

    App::redirect('/pacientes');
  }

  function handleEdition(int $id): void {
    try {
      $patient = $this->repository->getById($id);

      if (!$patient?->registeredBy->registeredBy->isEqualTo($this->loggedUser)) {
        throw new Error('Acceso denegado');
      }

      $patient->setFullName($this->data['full_name'])
        ->setIdCard($this->data['id_card']);

      $patient->birthDate = Date::from($this->data['birth_date'], '-');

      $this->repository->save($patient);
      parent::setMessage('Paciente actualizado exitósamente');
    } catch (Throwable $error) {
      parent::setError($error);
    }

    App::redirect('/pacientes');
  }
}
