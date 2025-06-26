<?php

declare(strict_types=1);

use HAJU\Models\Hospitalization;
use HAJU\Enums\DateRange;
use HAJU\Models\Department;

/**
 * @var Hospitalization[] $hospitalizations
 * @var Department $department
 */

?>

<section class="container mb-4 d-flex align-items-center justify-content-between">
  <h2>Hospitalizaciones</h2>
  <?php if (!$department->isHospitalization()) : ?>
    <a
      href="./hospitalizaciones/registrar"
      class="btn btn-primary d-flex align-items-center">
      <i class="px-2 fa fa-plus"></i>
      <span class="px-2">Registrar hospitalización</span>
    </a>
  <?php endif ?>
</section>

<section
  x-data='{
    patientName: "",
    allHospitalizations: JSON.parse(`<?= json_encode($hospitalizations) ?>`),
    startDate: "",

    get filteredHospitalizations() {
      return this.allHospitalizations.filter(hospitalization => {
        let match = hospitalization.patient.fullName.toLowerCase().includes(this.patientName.toLowerCase());

        if (this.startDate) {
          const startDate = new Date(this.startDate);
          const hospitalizationDate = new Date(hospitalization.admissionDateImperialFormat);

          match = match && hospitalizationDate >= startDate;
        }

        return match;
      });
    },
  }'
  class="container card card-body">
  <?php Flight::render('components/inputs/input', [
    'type' => 'search',
    'model' => 'patientName',
    'label' => 'Buscar por paciente...'
  ]) ?>

  <h3 class="my-4 fs-2">Filtrar por</h3>
  <div class="list-group mb-3">
    <div class="list-group-item d-flex gap-3 align-items-center">
      <label for="startDate">Desde</label>
      <input
        id="startDate"
        type="date"
        x-model="startDate"
        class="form-control w-auto" />
    </div>
    <div class="list-group-item d-flex gap-3 align-items-center">
      <?php foreach (DateRange::cases() as $dateRange) : ?>
        <?php Flight::render('components/input-group', [
          'name' => 'rango',
          'type' => 'radio',
          'placeholder' => $dateRange->value,
          'value' => $dateRange->getDate()->format('Y-m-d'),
          'model' => 'startDate',
        ]) ?>
      <?php endforeach ?>
    </div>
  </div>

  <div class="QA_table table-responsive">
    <table class="table text-center">
      <thead>
        <tr>
          <th></th>
          <th>Fecha de ingreso</th>
          <th>Paciente</th>
          <th>Departamento de ingreso</th>
          <th>Doctor</th>
        </tr>
      </thead>
      <tbody>
        <template
          x-for="hospitalization in (patientName || startDate) ? filteredHospitalizations : allHospitalizations"
          x-key="hospitalization.id">
          <tr>
            <td>
              <span
                class="badge"
                :class="`text-bg-${hospitalization.isFinished ? 'success' : 'danger'}`"
                x-text="hospitalization.isFinished ? 'Finalizada' : 'No finalizada'">
              </span>
            </td>
            <td x-text="hospitalization.admissionDate"></td>
            <td>
              <a
                class="link-primary text-decoration-underline"
                :href="`./pacientes/${hospitalization.patient.id}`"
                x-text="hospitalization.patient.fullName">
              </a>
            </td>
            <td x-text="hospitalization.admissionDepartment"></td>
            <td x-text="hospitalization.doctor.fullName"></td>
          </tr>
        </template>
      </tbody>
    </table>
  </div>
</section>
