<?php

declare(strict_types=1);

use HAJU\Models\Doctor;
use HAJU\Models\Hospitalization;
use HAJU\Models\Patient;
use HAJU\Enums\DepartureStatus;
use flight\Container;
use HAJU\Enums\AdmissionDepartment;
use Leaf\Http\Session;

/**
 * @var Patient $patient
 * @var Hospitalization $hospitalization
 * @var Doctor[] $doctors
 */

$session = Container::getInstance()->get(Session::class);

?>

<section class="mb-4 d-inline-flex px-0 align-items-center justify-content-between">
  <h2>Dar de alta a <?= $patient->getFullName() ?></h2>
</section>

<form
  action="./hospitalizaciones/<?= $hospitalization->id ?>"
  method="post"
  class="white_box d-flex flex-column align-items-center">
  <fieldset class="row w-100">
    <?php

    Flight::render('components/input-group', [
      'type' => 'date',
      'placeholder' => 'Fecha de ingreso',
      'cols' => 6,
      'name' => 'admission_date',
      'value' => $session->get('lastData', [])['admission_date'] ?? $hospitalization->admissionDate->format('Y-m-d')
    ]);

    Flight::render('components/input-group', [
      'type' => 'date',
      'placeholder' => 'Fecha de salida',
      'cols' => 6,
      'name' => 'departure_date',
      'value' => $session->get('lastData', [])['departure_date'] ?? date('Y-m-d'),
    ]);

    Flight::render('components/input-group', [
      'type' => 'select',
      'options' => array_map(fn(DepartureStatus $status): array => [
        'value' => $status->value,
        'text' => $status->value,
        'selected' => ($session->get('lastData', [])['admission_status'] ?? $hospitalization->departureStatus) === $status,
      ], DepartureStatus::cases()),
      'placeholder' => 'Estado de salida',
      'cols' => 6,
      'name' => 'departure_status',
      'hidden' => false,
    ]);

    Flight::render('components/input-group', [
      'type' => 'select',
      'options' => array_map(fn(AdmissionDepartment $department): array => [
        'value' => $department->value,
        'text' => $department->value,
        'selected' => $hospitalization->admissionDepartment === $department->value,
      ], AdmissionDepartment::cases()),
      'placeholder' => 'Departamento de ingreso',
      'cols' => 6,
      'name' => 'admission_department',
      'hidden' => false,
    ]);

    Flight::render('components/input-group', [
      'type' => 'select',
      'options' => array_map(fn(Doctor $doctor): array => [
        'value' => $doctor->id,
        'text' => "v-$doctor->idCard ~ $doctor->firstName $doctor->firstLastName",
        'selected' => $hospitalization->doctor->isEqualTo($doctor),
      ], $doctors),
      'placeholder' => 'Seleccione un doctor',
      'cols' => 6,
      'name' => 'doctor',
      'hidden' => false,
    ]);

    Flight::render('components/input-group', [
      'type' => 'textarea',
      'placeholder' => 'Diagnósticos',
      'cols' => 6,
      'name' => 'diagnoses',
      'hidden' => false,
    ]);

    ?>
  </fieldset>

  <button class="btn btn-primary btn-lg mt-4 px-5 rounded-pill">
    Dar alta
  </button>
</form>
