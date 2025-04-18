<?php

declare(strict_types=1);

use App\OldModels\Consultation;
use App\OldModels\Department;
use App\ValueObjects\DateRange;

/**
 * @var Consultation[] $consultations
 * @var Department $department
 */
assert(isset($consultations) && is_array($consultations), new Error('Consultations not set'));
assert(isset($department) && $department instanceof Department, new Error('Department not set'));

?>

<section class="mb-4 d-inline-flex align-items-center justify-content-between">
  <h2>Consultas</h2>
  <?php if (!$department->isStatistics()) : ?>
    <a
      href="./consultas/registrar"
      class="btn btn-primary rounded-pill d-flex align-items-center">
      <i class="px-2 ti-plus"></i>
      <span class="px-2">Registrar consulta</span>
    </a>
  <?php endif ?>
</section>

<section
  x-data='{
    patientName: "",
    type: "",
    allConsultations: JSON.parse(`<?= json_encode($consultations) ?>`),
    startDate: "",
    endDate: "",

    get filteredConsultations() {
      return this.allConsultations.filter(consultation => {
        let match = consultation.patient.fullName.toLowerCase().includes(this.patientName.toLowerCase());

        if (this.type) {
          match = match && consultation.type.letter.toLowerCase().includes(this.type.toLowerCase());
        }

        if (this.startDate) {
          const startDate = new Date(this.startDate);
          const consultationDate = new Date(consultation.registeredDateImperialFormat);

          match = match || consultationDate >= startDate;
        }

        if (this.endDate) {
          const endDate = new Date(this.endDate);
          const consultationDate = new Date(consultation.registeredDateImperialFormat);

          match = match || consultationDate <= endDate;
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
    <div
      class="list-group-item d-flex justify-content-between align-items-center">
      <select x-model="type" class="form-select">
        <option value="" selected>Tipo de consulta</option>
        <option value="P">P: Primera vez</option>
        <option value="S">S: Sucesiva</option>
        <option value="X">X: Asociada</option>
      </select>
    </div>

    <div class="list-group-item d-flex gap-3 align-items-center">
      <label for="startDate">Desde</label>
      <input
        id="startDate"
        type="date"
        x-model="startDate"
        class="form-control w-auto" />
      <label for="endDate">Hasta</label>
      <input
        id="endDate"
        type="date"
        x-model="endDate"
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
          <th>Fecha</th>
          <th>Paciente</th>
          <th>Tipo</th>
          <th>Causa de consulta</th>
          <th>Categoría de causa de consulta</th>
          <th>Doctor</th>
        </tr>
      </thead>
      <tbody>
        <template
          x-for="consultation in (patientName || type || startDate || endDate) ? filteredConsultations : allConsultations"
          x-key="consultation.id">
          <tr>
            <td x-text="consultation.registeredDate"></td>
            <td>
              <a
                class="link-primary text-decoration-underline"
                :href="`./pacientes/${consultation.patient.id}`"
                x-text="consultation.patient.fullName">
              </a>
            </td>
            <td x-text="consultation.type.letter"></td>
            <td x-text="consultation.cause.extendedName || consultation.cause.shortName"></td>
            <td x-text="consultation.cause.category.extendedName || consultation.cause.category.shortName"></td>
            <td x-text="consultation.doctor.fullName"></td>
          </tr>
        </template>
      </tbody>
    </table>
  </div>
</section>
