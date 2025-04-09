<?php

declare(strict_types=1);

use App\Models\Hospitalization;
use App\ValueObjects\DateRange;

/**
 * @var Hospitalization[] $hospitalizations
 */

?>

<section class="mb-4 d-inline-flex align-items-center justify-content-between">
  <h2>Hospitalizaciones</h2>
  <?php if (!$department->isHospitalization()) : ?>
    <a
      href="./hospitalizaciones/registrar"
      class="btn btn-primary rounded-pill d-flex align-items-center">
      <i class="px-2 ti-plus"></i>
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
          const hospitalizationDate = new Date(hospitalization.registeredDateImperialFormat);

          match = match || hospitalizationDate >= startDate;
        }

        return match;
      });
    },
  }'
  class="white_box QA_section">
  <header class="list_header serach_field-area2 w-100 mb-3">
    <form @submit.prevent class="search_inner w-100">
      <input
        type="search"
        placeholder="Buscar por paciente..."
        x-model="patientName" />
      <button>
        <i class="ti-search fs-2"></i>
      </button>
    </form>
  </header>

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
      <?php foreach (DateRange::cases() as $dateRange): ?>
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
