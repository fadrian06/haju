<?php

use App\Models\Patient;
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

<div class="table-responsive">
  <table class="table">
    <thead>
      <tr>
        <th>Nombre completo</th>
        <th>Cédula</th>
        <th>Fecha de nacimiento</th>
        <th>Género</th>
        <th>Registrado por</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($patients as $patient): ?>
        <tr>
          <td><?= $patient->getFullName() ?></td>
          <td><?= $patient->idCard ?></td>
          <td><?= $patient->birthDate ?></td>
          <td><?= $patient->gender->value ?></td>
          <td title="<?= $patient->registeredBy->getFullName() ?>">
            <?= $patient->registeredBy->firstName ?>
          </td>
          <td></td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</div>

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
