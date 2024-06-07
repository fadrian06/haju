<?php

namespace App\Controllers\Web;

use App;
use App\Controllers\Web\Controller;
use App\Models\Doctor;
use App\Repositories\Domain\DoctorRepository;
use App\ValueObjects\Date;
use App\ValueObjects\Gender;
use Error;
use Throwable;

final class DoctorWebController extends Controller {
  private readonly DoctorRepository $repository;

  function __construct() {
    parent::__construct();

    $this->repository = App::doctorRepository();
  }

  function showDoctors(): void {
    App::renderPage('doctors/list', 'Doctores', [
      'doctors' => $this->repository->getAll()
    ], 'main');
  }

  function handleRegister(): void {
    try {
      if (!in_array($this->data['gender'], Gender::values())) {
        throw new Error(sprintf(
          'El género es requerido y válido (%s)',
          join(', ', Gender::values())
        ));
      }

      $doctor = new Doctor(
        $this->data['first_name'],
        $this->data['second_name'],
        $this->data['first_last_name'],
        $this->data['second_last_name'],
        Date::from($this->data['birth_date'], '-'),
        Gender::from($this->data['gender']),
        $this->data['id_card'],
        $this->loggedUser
      );

      $this->repository->save($doctor);
      parent::setMessage('Doctor registrado exitósamente');
    } catch (Throwable $error) {
      parent::setError($error);
    }

    App::redirect(App::request()->referrer);
  }

  function showEdit(int $idCard): void {
    App::renderPage('doctors/edit', 'Editar doctor', [
      'doctor' => $this->repository->getByIdCard($idCard)
    ], 'main');
  }

  function handleEdition(int $idCard): void {
    try {
      $doctor = $this->repository->getByIdCard($idCard);

      $doctor
        ->setFirstName($this->data['first_name'])
        ->setSecondName($this->data['second_name'] ?: null)
        ->setFirstLastName($this->data['first_last_name'])
        ->setSecondLastName($this->data['second_last_name'] ?: null)
        ->setIdCard($this->data['id_card']);

      $doctor->birthDate = Date::from($this->data['birth_date'], '-');

      $this->repository->save($doctor);
      parent::setMessage('Doctor actualizado exitósamente');
    } catch (Throwable $error) {
      parent::setError($error);
    }

    App::redirect('/doctores');
  }
}
