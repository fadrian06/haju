<?php

use App\Models\User;
use App\ValueObjects\Appointment;
use App\ValueObjects\Gender;
use App\ValueObjects\InstructionLevel;

/**
 * @var array<int, User> $users
 * @var User $user
 */

$loggedUser = $user;

?>

<section class="mb-4 d-inline-flex px-0 align-items-center justify-content-between">
  <h2>Usuarios</h2>
  <a data-bs-toggle="modal" href="#registrar" class="btn btn-primary rounded-pill d-flex align-items-center">
    <i class="px-2 ti-plus"></i>
    <span class="px-2">
      Registrar
      <?= $user->appointment === Appointment::Director ? 'coordinador/a' : 'secretario/a' ?>
    </span>
  </a>
</section>

<ul class="list-unstyled row row-cols-sm-2 row-cols-md-3">
  <?php foreach ($users as $member) : ?>
    <li class="mb-4 d-flex align-items-stretch">
      <article class="card card-body text-center <?= !$member->registeredBy?->isEqualTo($loggedUser) ? 'pe-none opacity-50 user-select-none' : '' ?>">
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
        <?php if ($member->registeredBy !== null) : ?>
          <small class="text-muted" title="<?= $member->registeredBy->getFullName() ?>">
            Registrado por: <?= $member->registeredBy->firstName ?>
          </small>
        <?php endif ?>
      </article>
    </li>
  <?php endforeach ?>
</ul>

<div class="modal modal-xl fade" id="registrar">
  <div class="modal-dialog modal-dialog-scrollable">
    <form enctype="multipart/form-data" action="./usuarios#registrar" class="modal-content" method="post">
      <header class="modal-header">
        <h3 class="modal-title fs-5">
          Registrar
          <?= $user->appointment === Appointment::Director ? 'coordinador/a' : 'secretario/a' ?>
        </h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </header>
      <section class="modal-body">
        <fieldset class="row">
          <summary class="fs-6 mb-2">Datos personales</summary>
          <?php
          render('components/input-group', [
            'name' => 'first_name',
            'placeholder' => 'Primer nombre',
            'value' => $_SESSION['lastData']['first_name'] ?? ''
          ]);

          render('components/input-group', [
            'name' => 'second_name',
            'placeholder' => 'Segundo nombre',
            'required' => false,
            'value' => $_SESSION['lastData']['second_name'] ?? ''
          ]);

          render('components/input-group', [
            'name' => 'first_last_name',
            'placeholder' => 'Primer apellido',
            'required' => true,
            'value' => $_SESSION['lastData']['first_last_name'] ?? ''
          ]);

          render('components/input-group', [
            'name' => 'second_last_name',
            'placeholder' => 'Segundo apellido',
            'required' => false,
            'value' => $_SESSION['lastData']['second_last_name'] ?? ''
          ]);

          render('components/input-group', [
            'type' => 'number',
            'name' => 'id_card',
            'placeholder' => 'Cédula',
            'required' => true,
            'value' => $_SESSION['lastData']['id_card'] ?? ''
          ]);

          render('components/input-group', [
            'type' => 'date',
            'name' => 'birth_date',
            'placeholder' => 'Fecha de nacimiento',
            'value' => $_SESSION['lastData']['birth_date'] ?? ''
          ]);

          render('components/input-group', [
            'variant' => 'select',
            'name' => 'gender',
            'placeholder' => 'Género',
            'required' => true,
            'value' => null,
            'options' => array_map(
              fn(Gender $gender): array => [
                'value' => $gender->value,
                'text' => $gender->value,
                'selected' => ($_SESSION['lastData']['gender'] ?? '') === $gender->value
              ],
              Gender::cases()
            )
          ]);

          render('components/input-group', [
            'variant' => 'select',
            'name' => 'instruction_level',
            'placeholder' => 'Nivel de instrucción',
            'value' => null,
            'options' => array_map(
              fn(InstructionLevel $instruction): array => [
                'value' => $instruction->value,
                'text' => $instruction->getLongValue(),
                'selected' => ($_SESSION['lastData']['instruction_level'] ?? '') === $instruction->value
              ],
              InstructionLevel::cases()
            )
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
                <option
                  value="<?= $department->id ?>"
                  <?= in_array($department->id, $_SESSION['lastData']['departments'] ?? []) ? 'selected' : '' ?>>
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
            'value' => $_SESSION['lastData']['phone'] ?? ''
          ]);

          render('components/input-group', [
            'type' => 'email',
            'name' => 'email',
            'placeholder' => 'Correo electrónico',
            'value' => $_SESSION['lastData']['email'] ?? ''
          ]);

          render('components/input-group', [
            'variant' => 'textarea',
            'name' => 'address',
            'placeholder' => 'Dirección',
            'cols' => 12,
            'value' => $_SESSION['lastData']['address'] ?? ''
          ]);

          ?>
        </fieldset>
        <fieldset class="row">
          <?php

          render('components/input-group', [
            'variant' => 'file',
            'name' => 'profile_image',
            'placeholder' => 'Foto de perfil',
            'cols' => 5
          ]);

          echo '<div class="col-md-2 text-center">O</div>';

          render('components/input-group', [
            'variant' => 'input',
            'type' => 'url',
            'name' => 'profile_image_url',
            'placeholder' => 'URL de la foto de perfil',
            'cols' => 5,
            'value' => $_SESSION['lastData']['profile_image_url'] ?? ''
          ]);

          render('components/input-group', [
            'variant' => 'checkbox',
            'name' => 'is_active',
            'placeholder' => 'Estado <small>(activo/inactivo)</small>',
            'checked' => true
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

  /** @type {HTMLInputElement} */
  const $profileFileInput = document.querySelector('[name=profile_image]')

  /** @type {HTMLInputElement} */
  const $profileUrlInput = document.querySelector('[name=profile_image_url]')

  $profileFileInput.addEventListener('change', () => {
    $profileUrlInput.removeAttribute('required')
  })

  function toggleProfileInputsHandler() {
    if ($profileUrlInput.value) {
      $profileFileInput.removeAttribute('required')
    } else {
      $profileFileInput.setAttribute('required', true)
    }
  }

  $profileUrlInput.addEventListener('keydown', toggleProfileInputsHandler)
  $profileUrlInput.addEventListener('change', toggleProfileInputsHandler)
</script>
