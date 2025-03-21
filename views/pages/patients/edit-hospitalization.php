<?php

declare(strict_types=1);

use App\Models\Doctor;
use App\Models\Hospitalization;
use App\Models\Patient;
use App\ValueObjects\AdmissionDepartment;
use App\ValueObjects\DepartureStatus;
use Leaf\Http\Session;

/**
 * @var Patient $patient
 * @var Hospitalization $hospitalization
 * @var Doctor[] $doctors
 */

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
    render('components/input-group', [
      'variant' => 'input',
      'type' => 'date',
      'placeholder' => 'Fecha de ingreso',
      'cols' => 6,
      'name' => 'admission_date',
      'value' => Session::get('lastData', [])['admission_date'] ?? $hospitalization->admissionDate->format('Y-m-d')
    ]);

    render('components/input-group', [
      'variant' => 'input',
      'type' => 'date',
      'placeholder' => 'Fecha de salida',
      'cols' => 6,
      'name' => 'departure_date',
      'required' => true,
      'value' => Session::get('lastData', [])['departure_date'] ?? date('Y-m-d')
    ]);

    render('components/input-group', [
      'variant' => 'select',
      'options' => array_map(fn (DepartureStatus $status): array => [
        'value' => $status->value,
        'text' => $status->value,
        'selected' => (Session::get('lastData', [])['admission_status'] ?? $hospitalization->departureStatus) === $status,
      ], DepartureStatus::cases()),
      'placeholder' => 'Estado de salida',
      'cols' => 6,
      'name' => 'departure_status',
      'hidden' => false,
      'required' => true
    ]);

    render('components/input-group', [
      'variant' => 'select',
      'options' => array_map(fn (AdmissionDepartment $department): array => [
        'value' => $department->value,
        'text' => $department->value,
        'selected' => $hospitalization->admissionDepartment === $department->value,
      ], AdmissionDepartment::cases()),
      'placeholder' => 'Departamento de ingreso',
      'cols' => 6,
      'name' => 'admission_department',
      'hidden' => false,
      'required' => true
    ]);

    render('components/input-group', [
      'variant' => 'select',
      'options' => array_map(fn (Doctor $doctor): array => [
        'value' => $doctor->id,
        'text' => "v-$doctor->idCard ~ $doctor->firstName $doctor->firstLastName",
        'selected' => $hospitalization->doctor->isEqualTo($doctor),
      ], $doctors),
      'placeholder' => 'Seleccione un doctor',
      'cols' => 6,
      'name' => 'doctor',
      'hidden' => false
    ]);

    render('components/input-group', [
      'variant' => 'textarea',
      'placeholder' => 'Diagnósticos',
      'cols' => 6,
      'name' => 'diagnoses',
      'hidden' => false,
      'required' => true
    ]);

    ?>
  </fieldset>

  <button class="btn btn-primary btn-lg mt-4 px-5 rounded-pill">
    Dar alta
  </button>
</form>
