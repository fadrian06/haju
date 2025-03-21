<?php

declare(strict_types=1);

use App\Models\Patient;
use App\Models\User;

/**
 * @var array<int, Patient> $patients
 * @var ?string $error
 * @var ?string $message
 * @var User $user
 */

$loggedUser = $user;

?>

<section class="mb-4 d-inline-flex align-items-center justify-content-between">
  <h2>Pacientes</h2>
  <a
    data-bs-toggle="modal"
    href="#registrar"
    class="btn btn-primary rounded-pill d-flex align-items-center">
    <i class="px-2 ti-plus"></i>
    <span class="px-2">Registrar paciente</span>
  </a>
</section>

<?php if ($patients !== null) : ?>
  <section class="white_box QA_section">
    <!-- <header class="list_header serach_field-area2 w-100">
      <form class="search_inner w-100">
        <input type="search" placeholder="Buscar por nombre...">
        <button>
          <i class="ti-search fs-2"></i>
        </button>
      </form>
    </header> -->
    <div class="QA_table table-responsive">
      <table class="table text-center">
        <thead>
          <tr>
            <th></th>
            <th>Nombre completo</th>
            <th>Cédula</th>
            <th>Fecha de nacimiento</th>
            <th>Género</th>
            <th>Registrado por</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($patients as $patient) : ?>
            <tr>
              <form method="post" action="./pacientes/<?= $patient->id ?>">
                <td class="p-2">
                  <a
                    class="btn btn-secondary btn-sm text-white"
                    href="./pacientes/<?= $patient->id ?>">
                    Detalles
                  </a>
                </td>
                <td class="p-1">
                  <input
                    <?= $patient->canBeEditedBy($loggedUser) ? '' : 'readonly' ?>
                    placeholder="Nombre del paciente"
                    class="form-control"
                    required
                    name="full_name"
                    value="<?= $patient->getFullName() ?>" />
                </td>
                <td class="p-1">
                  <input
                    <?= $patient->canBeEditedBy($loggedUser) ? '' : 'readonly' ?>
                    type="number"
                    placeholder="Cédula del paciente"
                    class="form-control"
                    required
                    name="id_card"
                    value="<?= $patient->idCard ?>" />
                </td>
                <td class="p-1">
                  <input
                    <?= $patient->canBeEditedBy($loggedUser) ? '' : 'readonly' ?>
                    type="date"
                    placeholder="Fecha de nacimiento"
                    class="form-control"
                    required
                    name="birth_date"
                    value="<?= $patient->birthDate->getWithDashes() ?>" />
                </td>
                <td>
                  <?= $patient->gender->value ?>
                </td>
                <td title="<?= $patient->registeredBy->getFullName() ?>">
                  <?= $patient->registeredBy->firstName ?>
                </td>
                <td class="p-2">
                  <div class="btn-group">
                    <?php if ($patient->canBeEditedBy($loggedUser)) : ?>
                      <button class="btn btn-sm btn-primary text-white">
                        Actualizar
                      </button>
                    <?php endif ?>
                    <?php if ($patient->canBeDeleted()) : ?>
                      <a
                        href="./pacientes/<?= $patient->id ?>/eliminar"
                        class="btn btn-sm btn-danger text-white">
                        Eliminar
                      </a>
                    <?php endif ?>
                  </div>
                </td>
              </form>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </section>
<?php endif ?>

<?php
render('forms/patient-register', ['action' => './pacientes#registrar']);
