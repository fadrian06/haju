<?php

use App\Models\Appointment;
use App\Models\Gender;
use App\Models\InstructionLevel;
use App\Models\User;

/**
 * @var array<int, User> $users
 * @var ?string $error
 * @var ?string $message
 * @var User $user
 */

?>

<section class="mb-4 d-inline-flex px-0 align-items-center justify-content-between">
  <h2>Usuarios</h2>
  <a data-bs-toggle="modal" href="#registrar" class="btn btn-primary rounded-pill d-flex align-items-center">
    <i class="px-2 ti-plus"></i>
    <span class="px-2">Añadir usuario</span>
  </a>
</section>
<ul class="list-unstyled row row-cols-sm-2 row-cols-md-3">
  <?php foreach ($users as $member) : ?>
    <li class="mb-4">
      <article class="card card-body text-center">
        <div class="dropdown position-relative">
          <button style="right: 0" class="bg-transparent border-0 position-absolute" data-bs-toggle="dropdown">
            <i class="ti-more"></i>
          </button>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="./usuarios/<?= $member->getId() ?>/<?= $member->getActiveStatus() ? 'desactivar' : 'activar' ?>">
              <i class="ti-<?= $member->getActiveStatus() ? 'un' : '' ?>lock"></i>
              <?= $member->getActiveStatus() ? 'Desactivar' : 'Activar' ?>
            </a>
          </div>
        </div>
        <picture class="p-3">
          <img class="img-fluid rounded-circle" src="<?= urldecode($member->profileImagePath->asString()) ?>" />
        </picture>
        <span class="custom-badge status-<?= $member->getActiveStatus() ? 'green' : 'red' ?> mx-4 mb-2">
          <?= $member->getActiveStatus() ? 'Activo' : 'Inactivo' ?>
        </span>
        <h4><?= $member->getFullName() ?></h4>
        <span><?= $member->getParsedAppointment() ?></span>
        <small class="text-muted">
          <i class="ti-pin2"></i>
          <?= $member->getAddress() ?>
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
              'placeholder' => 'Primer apellido'
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
              'type' => 'password',
              'name' => 'password',
              'placeholder' => 'Contraseña'
            ]);

            render('components/input-group', [
              'variant' => 'input',
              'type' => 'password',
              'name' => 'confirm_password',
              'placeholder' => 'Confirmar contraseña'
            ]);
          ?>
        </fieldset>
        <?php if ($user->appointment->isHigherThan(Appointment::Coordinator)) : ?>
          <div class="col-md-12 mb-4">
            <label for="departments">Departamentos asignados</label>
            <select name="departments[]" id="departments" required multiple class="form-control">
              <?php foreach ($user->getDepartment() as $department) : ?>
                <option value="<?= $department->getId() ?>">
                  <?= $department->getName() ?>
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
              'placeholder' => 'Teléfono'
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
