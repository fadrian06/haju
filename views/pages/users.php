<?php

use App\Models\User;
use App\ValueObjects\Appointment;
use App\ValueObjects\Gender;
use App\ValueObjects\InstructionLevel;

/**
 * @var array<int, User> $users
 * @var ?string $error
 * @var ?string $message
 * @var User $user
 */

$loggedUser = $user;

?>

<section class="mb-4 d-inline-flex px-0 align-items-center justify-content-between">
  <h2>Usuarios</h2>
  <a data-bs-toggle="modal" href="#registrar" class="btn btn-primary rounded-pill d-flex align-items-center">
    <i class="px-2 ti-plus"></i>
    <span class="px-2">Añadir usuario</span>
  </a>
</section>

<?php $error && render('components/notification', ['type' => 'error', 'text' => $error]) ?>
<?php $message && render('components/notification', ['type' => 'message', 'text' => $message]) ?>

<ul class="list-unstyled row row-cols-sm-2 row-cols-md-3">
  <?php foreach ($users as $member) : ?>
    <li class="mb-4 d-flex align-items-stretch">
      <article class="card card-body text-center <?= !$member->registeredBy->isEqualTo($loggedUser) ? 'pe-none opacity-50 user-select-none' : '' ?>">
        <div class="dropdown position-relative">
          <button style="right: 0" class="bg-transparent border-0 position-absolute" data-bs-toggle="dropdown">
            <i class="ti-more"></i>
          </button>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="./usuarios/<?= $member->id ?>/<?= $member->isActive() ? 'desactivar' : 'activar' ?>">
              <i class="ti-<?= $member->isActive() ? 'un' : '' ?>lock"></i>
              <?= $member->isActive() ? 'Desactivar' : 'Activar' ?>
            </a>
          </div>
        </div>
        <picture class="p-3">
          <img class="img-fluid rounded-circle" src="<?= urldecode($member->profileImagePath->asString()) ?>" style="height: 130px" title="<?= $member->getFullName() ?>" />
        </picture>
        <span class="custom-badge status-<?= $member->isActive() ? 'green' : 'red' ?> mx-4 mb-2">
          <?= $member->isActive() ? 'Activo' : 'Inactivo' ?>
        </span>
        <h4 title="<?= $member->getFullName() ?>">
          <?= "{$member->firstName} {$member->firstLastName}" ?>
        </h4>
        <span><?= $member->getParsedAppointment() ?></span>
        <small class="text-muted">
          <i class="ti-pin2"></i>
          <?= $member->address ?>
        </small>
        <small class="text-muted" title="<?= $member->registeredBy->getFullName() ?>">
          Registrado por: <?= $member->registeredBy->firstName ?>
        </small>
      </article>
    </li>
  <?php endforeach ?>
</ul>

<div class="modal fade" id="registrar">
  <div class="modal-dialog">
    <form enctype="multipart/form-data" action="./usuarios#registrar" class="modal-content" method="post">
      <header class="modal-header">
        <h3 class="modal-title fs-5">
          Registrar
          <?= $user->appointment === Appointment::Director ? 'coordinador/a' : 'secretario/a' ?>
        </h3>
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
            'options' => array_map(function (Gender $gender): array {
              return ['value' => $gender->value, 'text' => $gender->value];
            }, Gender::cases())
          ]);

          render('components/input-group', [
            'variant' => 'select',
            'name' => 'instruction_level',
            'placeholder' => 'Nivel de instrucción',
            'options' => array_map(function (InstructionLevel $instruction): array {
              return ['value' => $instruction->value, 'text' => $instruction->getLongValue()];
            }, InstructionLevel::cases())
          ]);
          ?>
        </fieldset>
        <fieldset class="row">
          <summary class="fs-6 mb-2">Credenciales</summary>
          <?php
          render('components/input-group', [
            'variant' => 'input',
            'type' => 'text',
            'name' => 'password',
            'placeholder' => 'Contraseña',
            'readonly' => true,
            'value' => '1234'
          ]);

          render('components/input-group', [
            'variant' => 'input',
            'type' => 'text',
            'name' => 'confirm_password',
            'placeholder' => 'Confirmar contraseña',
            'readonly' => true,
            'value' => '1234'
          ]);
          ?>
        </fieldset>
        <?php if ($user->appointment->isHigherThan(Appointment::Coordinator)) : ?>
          <div class="col-md-12 mb-4">
            <label for="departments" class="mb-2">
              Departamentos asignados
              <sub class="text-danger ms-2" style="font-size: 2em">*</sub>
            </label>
            <select name="departments[]" id="departments" required multiple class="form-control">
              <?php foreach ($user->getDepartment() as $department) : ?>
                <option value="<?= $department->id ?>">
                  <?= $department->name ?>
                </option>
              <?php endforeach ?>
            </select>
          </div>
        <?php endif ?>
        <fieldset class="row">
          <summary class="fs-6 mb-2">Datos de contacto</summary>
          <?php
          render('components/input-group', [
            'type' => 'tel',
            'name' => 'phone',
            'placeholder' => 'Teléfono',
            'readonly' => false,
            'value' => ''
          ]);

          render('components/input-group', [
            'type' => 'email',
            'name' => 'email',
            'placeholder' => 'Correo electrónico'
          ]);

          render('components/input-group', [
            'variant' => 'textarea',
            'name' => 'address',
            'placeholder' => 'Dirección',
            'cols' => 12
          ]);
          ?>
        </fieldset>
        <?php
        render('components/input-group', [
          'variant' => 'file',
          'name' => 'profile_image',
          'placeholder' => 'Foto de perfil',
          'cols' => 12
        ]);

        render('components/input-group', [
          'variant' => 'checkbox',
          'name' => 'is_active',
          'placeholder' => 'Estado <small>(activo/inactivo)</small>',
          'checked' => true
        ]);
        ?>
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