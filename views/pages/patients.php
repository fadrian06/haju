<?php

use App\Models\Patient;
use App\Models\User;
use App\ValueObjects\Gender;

/**
 * @var array<int, Patient> $patients
 * @var ?string $error
 * @var ?string $message
 * @var User $user
 */

$loggedUser = $user;

?>

<section class="mb-4 d-inline-flex px-0 align-items-center justify-content-between">
  <h2>Pacientes</h2>
  <a data-bs-toggle="modal" href="#registrar" class="btn btn-primary rounded-pill d-flex align-items-center">
    <i class="px-2 ti-plus"></i>
    <span class="px-2">Registrar paciente</span>
  </a>
</section>

<?php $error && render('components/notification', ['type' => 'error', 'text' => $error]) ?>
    <?php $message && render('components/notification', ['type' => 'message', 'text' => $message]) ?>

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
            <th>Nombre completo</th>
            <th>Cédula</th>
            <th>Fecha de nacimiento</th>
            <th>Género</th>
            <th>Registrado por</th>
            <th></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($patients as $patient) : ?>
            <?php $canEdit = $patient->registeredBy->registeredBy->isEqualTo($loggedUser) ?>
            <tr>
              <form method="post" action="./pacientes/<?= $patient->id ?>">
                <td class="p-1">
                  <input <?= $canEdit ? '' : 'readonly' ?> placeholder="Nombre del paciente" class="form-control" required name="full_name" value="<?= $patient->getFullName() ?>" />
                </td>
                <td class="p-1">
                  <input <?= $canEdit ? '' : 'readonly' ?> type="number" placeholder="Cédula del paciente" class="form-control" required name="id_card" value="<?= $patient->idCard ?>" />
                </td>
                <td class="p-1">
                  <input <?= $canEdit ? '' : 'readonly' ?> type="date" placeholder="Fecha de nacimiento" class="form-control" required name="birth_date" value="<?= $patient->birthDate->getWithDashes() ?>" />
                </td>
                <td>
                  <?= $patient->gender->value ?>
                </td>
                <td title="<?= $patient->registeredBy->getFullName() ?>">
                  <?= $patient->registeredBy->firstName ?>
                </td>
                <td class="p-2">
                  <?php if ($canEdit) : ?>
                    <button class="btn btn-sm btn-primary text-white">Actualizar</button>
                  <?php endif ?>
                </td>
                <td class="p-2">
                  <a class="btn btn-secondary btn-sm text-white" href="./pacientes/<?= $patient->id ?>">
                    Detalles
                  </a>
                </td>
              </form>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </section>
<?php endif ?>

<div class="modal fade" id="registrar">
  <div class="modal-dialog">
    <form action="./pacientes#registrar" class="modal-content" method="post">
      <header class="modal-header">
        <h3 class="modal-title fs-5">Registrar paciente</h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </header>
      <section class="modal-body">
        <?php $error &&  render('components/notification', ['type' => 'error', 'text' => $error]) ?>
        <?php $message &&  render('components/notification', ['type' => 'message', 'text' => $message]) ?>
        <fieldset class="row">
          <summary class="fs-6 mb-2">Datos personales</summary>
          <?php

          render('components/input-group', [
            'name' => 'first_name',
            'placeholder' => 'Primer nombre'
          ]);

          render('components/input-group', [
            'name' => 'second_name',
            'placeholder' => 'Segundo nombre',
            'required' => false
          ]);

          render('components/input-group', [
            'name' => 'first_last_name',
            'placeholder' => 'Primer apellido',
            'required' => true
          ]);

          render('components/input-group', [
            'name' => 'second_last_name',
            'placeholder' => 'Segundo apellido',
            'required' => false
          ]);

          render('components/input-group', [
            'type' => 'number',
            'name' => 'id_card',
            'placeholder' => 'Cédula',
            'required' => true
          ]);

          render('components/input-group', [
            'type' => 'date',
            'name' => 'birth_date',
            'placeholder' => 'Fecha de nacimiento'
          ]);

          render('components/input-group', [
            'variant' => 'select',
            'name' => 'gender',
            'placeholder' => 'Género',
            'options' => array_map(fn (Gender $gender): array => [
              'value' => $gender->value,
              'text' => $gender->value
            ], Gender::cases()),
            'cols' => 12
          ]);

          ?>
        </fieldset>
      </section>
      <footer class="modal-footer">
        <button class="btn btn-primary">Registrar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Cancelar
        </button>
      </footer>
    </form>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    if (location.href.endsWith('#registrar')) {
      new bootstrap.Modal('#registrar').show()
    }
  })
</script>
