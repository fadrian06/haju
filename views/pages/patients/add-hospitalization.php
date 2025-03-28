<?php

use App\Models\Doctor;
use App\Models\Patient;
use App\ValueObjects\AdmissionDepartment;
use App\ValueObjects\DepartureStatus;

/**
 * @var Patient[] $patients
 * @var Doctor[] $doctors
 */

?>

<section class="mb-4 d-inline-flex px-0 align-items-center justify-content-between">
  <h2>Registrar hospitalización</h2>
</section>

<form action="./hospitalizaciones" method="post" class="white_box d-flex flex-column align-items-center">
  <fieldset class="row w-100">
    <?php

    render('components/input-group', [
      'variant' => 'select',
      'options' => array_map(fn (Patient $patient): array => [
        'value' => $patient->id,
        'text' => "v-{$patient->idCard} ~ {$patient->getFullName()}"
      ], $patients),
      'placeholder' => 'Paciente',
      'name' => 'id_card',
      'cols' => 7
    ]);

    ?>

    <div class="col d-flex flex-column text-center mb-4 mb-md-0">
      ¿El paciente no está registrado?
      <div class="mt-2">
        <a href="#registrar" data-bs-toggle="modal" class="btn btn-secondary btn-sm">
          Regístralo
        </a>
      </div>
    </div>

    <?php

    render('components/input-group', [
      'variant' => 'input',
      'type' => 'date',
      'placeholder' => 'Fecha de ingreso',
      'cols' => 6,
      'name' => 'admission_date',
      'value' => date('Y-m-d')
    ]);

    render('components/input-group', [
      'variant' => 'input',
      'type' => 'date',
      'placeholder' => 'Fecha de salida',
      'cols' => 6,
      'name' => 'departure_date',
      'required' => false,
      'value' => ''
    ]);

    render('components/input-group', [
      'variant' => 'select',
      'options' => array_map(fn (DepartureStatus $status): array => [
        'value' => $status->value,
        'text' => $status->value
      ], DepartureStatus::cases()),
      'placeholder' => 'Estado de salida',
      'cols' => 6,
      'name' => 'admission_status',
      'hidden' => false,
      'required' => false
    ]);

    render('components/input-group', [
      'variant' => 'select',
      'options' => array_map(fn (AdmissionDepartment $department): array => [
        'value' => $department->value,
        'text' => $department->value
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
        'text' => "v-$doctor->idCard ~ $doctor->firstName $doctor->firstLastName"
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
      'required' => false
    ]);

    ?>
  </fieldset>

  <button id="register-btn" class="btn btn-primary btn-lg mt-4 px-5 rounded-pill">
    Registrar
  </button>
</form>
