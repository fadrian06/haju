<?php

use App\Models\ConsultationCauseCategory;
use App\Models\Department;
use App\Models\Patient;
use App\ValueObjects\ConsultationType;

/**
 * @var array<int, Patient> $patients
 * @var array<int, ConsultationCauseCategory> $consultationCauseCategories
 * @var array<int, Department> $departments
 */

?>

<section class="mb-4 d-inline-flex px-0 align-items-center justify-content-between">
  <h2>Registrar consulta</h2>
</section>

<form action="./consultas" method="post" class="white_box d-flex flex-column align-items-center">
  <fieldset class="row w-100">
    <?php

    render('components/input-group', [
      'variant' => 'select',
      'options' => array_map(fn (Patient $patient): array => [
        'value' => $patient->id,
        'text' => "v-{$patient->idCard} ~ {$patient->getFullName()}"
      ], $patients),
      'placeholder' => 'Buscar cédula...',
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
      'variant' => 'select',
      'options' => array_map(fn (ConsultationCauseCategory $category): array => [
        'value' => $category->id,
        'text' => $category->extendedName ?? $category->shortName
      ], $consultationCauseCategories),
      'placeholder' => 'Categoría de causa de consulta',
      'cols' => 7,
      'name' => 'consultation_cause_category'
    ]);

    render('components/input-group', [
      'variant' => 'select',
      'hidden' => true,
      'options' => [],
      'placeholder' => 'Causa de consulta',
      'cols' => 5,
      'name' => 'consultation_cause'
    ]);

    render('components/input-group', [
      'variant' => 'select',
      'hidden' => true,
      'options' => array_map(fn (ConsultationType $type): array => [
        'value' => $type->value,
        'text' => $type->getDescription()
      ], ConsultationType::getCases()),
      'placeholder' => 'Tipo de consulta',
      'cols' => 6,
      'name' => 'consultation_type'
    ]);

    render('components/input-group', [
      'variant' => 'select',
      'options' => array_map(fn (Department $department): array => [
        'value' => $department->id,
        'text' => $department->name
      ], $departments),
      'placeholder' => 'Seleccione un departamento',
      'cols' => 6,
      'name' => 'department',
      'hidden' => false
    ]);

    ?>

    <!-- <div class="nice-select default_sel mb_30 w-100" tabindex="0"><span class="current">Select</span>
      <div class="nice-select-search-box"><input class="nice-select-search" placeholder="Search..." type="text"></div>
      <ul class="list">
        <li data-value="Nothing" data-display="Select" class="option selected focus">Nothing</li>
        <li data-value="1" class="option">Some option</li>
        <li data-value="2" class="option">Another option</li>
        <li data-value="3" class="option disabled">A disabled option</li>
        <li data-value="4" class="option">Potato</li>
      </ul>
    </div> -->
  </fieldset>
  <button id="register-btn" class="btn btn-primary d-none btn-lg mt-4 px-5 rounded-pill">Registrar</button>
</form>

<?php render('forms/patient-register', ['action' => './pacientes?referido=/consultas/registrar']) ?>

<script>
  const $consultationCauseSelect = document.querySelector('[name="consultation_cause"]')
  const $idCardSelect = document.querySelector('[name="id_card"]')
  const $registerBtn = document.getElementById('register-btn')
  const $consultationTypeSelect = document.querySelector('[name="consultation_type"]')

  const causesByCategory = {}

  document.querySelector('[name="consultation_cause_category"]').addEventListener('change', async event => {
    if (!causesByCategory[event.target.value]) {
      const response = await fetch(`./api/causas-consulta/categorias/${event.target.value}`)
      const {
        consultationCauses
      } = await response.json()

      causesByCategory[event.target.value] = consultationCauses
    }

    let html = '<option selected disabled>Seleccione una opción</option>'

    for (const cause of causesByCategory[event.target.value]) {
      html += `<option value="${cause.id}">${cause.name.extended || cause.name.short}</option>`
    }

    $consultationCauseSelect.innerHTML = html
    $consultationCauseSelect.parentElement.classList.remove('d-none')
  })

  $consultationCauseSelect.addEventListener('change', async event => {
    const response = await fetch(`./api/pacientes/${$idCardSelect.value}/causas-consulta/${$consultationCauseSelect.value}`)
    const patientConsultation = await response.json()

    if (patientConsultation.isFirstTime) {
      $consultationTypeSelect.disabled = true
      $consultationTypeSelect.parentElement.classList.add('d-none')
    } else {
      $consultationTypeSelect.removeAttribute('disabled')
      $consultationTypeSelect.parentElement.classList.remove('d-none')
    }

    $registerBtn.classList.remove('d-none')
  })
</script>
