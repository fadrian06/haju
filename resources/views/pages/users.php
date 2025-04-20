<?php

declare(strict_types=1);

use HAJU\Models\User;
use HAJU\Enums\Appointment;
use HAJU\Enums\Gender;
use HAJU\Enums\InstructionLevel;
use flight\Container;
use Leaf\Http\Session;

/**
 * @var array<int, User> $users
 * @var User $user
 */

$loggedUser = $user;
$session = Container::getInstance()->get(Session::class);

?>

<section class="mb-4 d-inline-flex px-0 align-items-center justify-content-between">
  <h2>Usuarios</h2>
  <a data-bs-toggle="modal" href="#registrar" class="btn btn-primary rounded-pill d-flex align-items-center">
    <i class="px-2 ti-plus"></i>
    <span class="px-2">
      Registrar
      <?= $user->appointment->isDirector() ? 'coordinador/a' : 'secretario/a' ?>
    </span>
  </a>
</section>

<ul class="list-unstyled row row-cols-sm-2 row-cols-md-3">
  <?php foreach ($users as $member) : ?>
    <li class="mb-4 d-flex align-items-stretch">
      <article class="card card-body text-center <?= $member->registeredBy?->isEqualTo($loggedUser) ?: 'pe-none opacity-50 user-select-none' ?>">
        <div class="dropdown position-relative">
          <button style="right: 0" class="bg-transparent border-0 position-absolute" data-bs-toggle="dropdown">
            <i class="ti-more"></i>
          </button>
          <div class="dropdown-menu">
            <a
              class="dropdown-item"
              href="./usuarios/<?= $member->id ?>/<?= $member->isActive() ? 'desactivar' : 'activar' ?>">
              <i class="ti-<?= $member->isActive() ? 'un' : '' ?>lock"></i>
              <?= $member->isActive() ? 'Desactivar' : 'Activar' ?>
            </a>
          </div>
        </div>
        <picture class="p-3">
          <img
            class="img-fluid rounded-circle"
            src="<?= urldecode($member->profileImagePath->asString()) ?>"
            style="height: 130px"
            title="<?= $member->getFullName() ?>" />
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

<div class="modal fade" id="registrar">
  <div class="modal-dialog modal-dialog-scrollable">
    <form enctype="multipart/form-data" action="./usuarios#registrar" class="modal-content" method="post">
      <header class="modal-header">
        <h3 class="modal-title fs-5">
          Registrar
          <?= $user->appointment->isDirector() ? 'coordinador/a' : 'secretario/a' ?>
        </h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </header>
      <section
        class="modal-body"
        x-data="{ idCard: '<?= $session->get('lastData')['id_card'] ?? '' ?>' }">
        <fieldset class="row row-gap-3 mb-3">
          <summary class="fs-6 mb-2">Datos personales</summary>
          <div class="col-md-6">
            <?php Flight::render('components/inputs/input', [
              'name' => 'first_name',
              'label' => 'Primer nombre',
              'value' => $session->get('lastData')['first_name'] ?? '',
            ]) ?>
          </div>

          <div class="col-md-6">
            <?php Flight::render('components/inputs/input', [
              'name' => 'second_name',
              'label' => 'Segundo nombre',
              'required' => false,
              'value' => $session->get('lastData')['second_name'] ?? '',
            ]) ?>
          </div>

          <div class="col-md-6">
            <?php Flight::render('components/inputs/input', [
              'name' => 'first_last_name',
              'label' => 'Primer apellido',
              'value' => $session->get('lastData')['first_last_name'] ?? '',
            ]) ?>
          </div>

          <div class="col-md-6">
            <?php Flight::render('components/inputs/input', [
              'name' => 'second_last_name',
              'label' => 'Segundo apellido',
              'required' => false,
              'value' => $session->get('lastData')['second_last_name'] ?? '',
            ]) ?>
          </div>

          <div class="col-md-6">
            <?php Flight::render('components/inputs/input', [
              'type' => 'number',
              'name' => 'id_card',
              'label' => 'Cédula',
              'value' => $session->get('lastData')['id_card'] ?? '',
              'model' => 'idCard',
            ]) ?>
          </div>

          <div class="col-md-6">
            <?php Flight::render('components/inputs/input', [
              'type' => 'date',
              'name' => 'birth_date',
              'label' => 'Fecha de nacimiento',
              'value' => $session->get('lastData')['birth_date'] ?? '',
            ]) ?>
          </div>

          <div class="col-md-6">
            <?php Flight::render('components/inputs/select', [
              'name' => 'gender',
              'label' => 'Género',
              'options' => array_map(
                static fn(Gender $gender): array => [
                  'value' => $gender->value,
                  'slot' => $gender->value,
                  'selected' => ($session->get('lastData')['gender'] ?? '') === $gender->value,
                ],
                Gender::cases()
              ),
            ]) ?>
          </div>

          <div class="col-md-6">
            <?php Flight::render('components/inputs/select', [
              'name' => 'instruction_level',
              'label' => 'Nivel de instrucción',
              'options' => array_map(
                static fn(InstructionLevel $instruction): array => [
                  'value' => $instruction->value,
                  'slot' => $instruction->getLongValue(),
                  'selected' => ($session->get('lastData')['instruction_level'] ?? '') === $instruction->value,
                ],
                InstructionLevel::cases(),
              ),
            ]) ?>
          </div>
        </fieldset>
        <fieldset class="row row-gap-3 mb-3">
          <summary class="fs-6 mb-2">Credenciales</summary>

          <div class="col-md-6">
            <?php Flight::render('components/inputs/input', [
              'name' => 'password',
              'label' => 'Contraseña',
              'readonly' => true,
              'required' => false,
              'value' => 'idCard',
            ]) ?>
          </div>

          <div class="col-md-6">
            <?php Flight::render('components/inputs/input', [
              'name' => 'confirm_password',
              'label' => 'Confirmar contraseña',
              'readonly' => true,
              'value' => 'idCard',
              'required' => false,
            ]) ?>
          </div>
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
                  <?= in_array($department->id, $session->get('lastData')['departments'] ?? [], true) ? 'selected' : '' ?>>
                  <?= $department->name ?>
                </option>
              <?php endforeach ?>
            </select>
          </div>
        <?php endif ?>
        <fieldset class="row row-gap-3 mb-3">
          <summary class="fs-6 mb-2">Datos de contacto</summary>

          <div class="col-md-6">
            <?php Flight::render('components/inputs/input', [
              'type' => 'tel',
              'name' => 'phone',
              'label' => 'Teléfono',
              'readonly' => false,
              'value' => $session->get('lastData')['phone'] ?? '',
            ]) ?>
          </div>

          <div class="col-md-6">
            <?php Flight::render('components/inputs/input', [
              'type' => 'email',
              'name' => 'email',
              'label' => 'Correo electrónico',
              'value' => $session->get('lastData')['email'] ?? '',
            ]) ?>
          </div>

          <div class="col-md-12">
            <?php Flight::render('components/inputs/textarea', [
              'name' => 'address',
              'label' => 'Dirección',
              'value' => $session->get('lastData')['address'] ?? '',
            ]) ?>
          </div>
        </fieldset>
        <fieldset class="row row-gap-3 mb-3 align-items-center">
          <div class="col-md-5">
            <?php Flight::render('components/inputs/input-file', [
              'name' => 'profile_image',
              'label' => 'Foto de perfil',
            ]) ?>
          </div>

          <div class="col-md-2 text-center">O</div>

          <div class="col-md-5">
            <?php Flight::render('components/inputs/input', [
              'type' => 'url',
              'name' => 'profile_image_url',
              'label' => 'URL de la foto de perfil',
              'value' => $session->get('lastData')['profile_image_url'] ?? '',
            ]) ?>
          </div>

          <?php Flight::render('components/input-group', [
            'type' => 'checkbox',
            'name' => 'is_active',
            'placeholder' => 'Estado <small>(activo/inactivo)</small>',
            'checked' => true,
          ]) ?>
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
