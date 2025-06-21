<?php

declare(strict_types=1);

use flight\util\Collection;
use HAJU\Enums\Gender;
use HAJU\InstructionLevels\Domain\InstructionLevel;

/**
 * @var Collection $lastData
 * @var InstructionLevel[] $instructionLevels
 */

?>

<main class="container rounded-3 my-4 py-4 d-flex flex-column justify-content-center">
  <div class="row justify-content-center">
    <div class="col-11 col-md-8">
      <form
        method="post"
        class="card text-center"
        enctype="multipart/form-data">
        <div class="card-header border-bottom-0 py-3 px-4">
          <h1 class="card-title fw-bold h5 m-0">Regístrate como director</h1>
        </div>
        <div class="card-body text-center px-4 pb-4 d-grid gap-4">
          <fieldset class="row pb-4 row-gap-4 border-bottom">
            <legend class="h5 mb-2 text-start">Datos personales</legend>
            <div class="col-md-6">
              <?php Flight::render('components/inputs/input', [
                'name' => 'first_name',
                'label' => 'Primer nombre',
                'value' => $lastData['first_name'] ?? '',
              ]) ?>
            </div>
            <div class="col-md-6">
              <?php Flight::render('components/inputs/input', [
                'name' => 'second_name',
                'label' => 'Segundo nombre',
                'required' => false,
                'value' => $lastData['second_name'] ?? '',
              ]) ?>
            </div>
            <div class="col-md-6">
              <?php Flight::render('components/inputs/input', [
                'name' => 'first_last_name',
                'label' => 'Primer apellido',
                'value' => $lastData['first_last_name'] ?? '',
              ]) ?>
            </div>
            <div class="col-md-6">
              <?php Flight::render('components/inputs/input', [
                'name' => 'second_last_name',
                'label' => 'Segundo apellido',
                'required' => false,
                'value' => $lastData['second_last_name'] ?? '',
              ]) ?>
            </div>
            <div class="col-md-6">
              <?php Flight::render('components/inputs/input', [
                'type' => 'date',
                'name' => 'birth_date',
                'label' => 'Fecha de nacimiento',
                'value' => $lastData['birth_date'] ?? '',
              ]) ?>
            </div>
            <div class="col-md-6">
              <?php Flight::render('components/inputs/select', [
                'name' => 'gender',
                'options' => array_map(static fn(Gender $gender): array => [
                  'slot' => $gender->value,
                  'value' => $gender->value,
                  'selected' => $gender->value === ($lastData['gender'] ?? ''),
                ], Gender::cases()),
                'label' => 'Género',
              ]) ?>
            </div>
            <div class="col-md-6">
              <?php Flight::render('components/inputs/instruction-level-selector', [
                'instructionLevels' => $instructionLevels,
              ]) ?>
            </div>
            <div class="col-md-6 text-start">
              <?php Flight::render('components/inputs/input-file', [
                'name' => 'profile_image',
                'label' => 'Foto de perfil',
              ]) ?>
            </div>
          </fieldset>
          <fieldset
            class="row pb-4 row-gap-4 border-bottom"
            x-data="{ password: '' }">
            <legend class="h5 mb-2 text-start">Credenciales de ingreso</legend>
            <div class="col-md-12">
              <?php Flight::render('components/inputs/input', [
                'type' => 'number',
                'name' => 'id_card',
                'label' => 'Cédula',
                'value' => $lastData['id_card'] ?? '',
              ]) ?>
            </div>
            <div class="col-md-6">
              <?php Flight::render('components/inputs/input-password', [
                'name' => 'password',
                'label' => 'Contraseña',
                'value' => $lastData['password'] ?? '',
                'model' => 'password',
              ]) ?>
            </div>
            <div class="col-md-6">
              <?php Flight::render('components/inputs/input-password', [
                'name' => 'confirm_password',
                'label' => 'Confirmar contraseña',
                'value' => $lastData['confirm_password'] ?? '',
                'pattern' => 'password',
                'title' => 'Ambas contraseñas deben coincidir',
              ]) ?>
            </div>
          </fieldset>
          <fieldset class="row pb-4 row-gap-4 border-bottom">
            <legend class="h5 mb-2 text-start">Datos de contacto</legend>
            <div class="col-md-6">
              <?php Flight::render('components/inputs/input', [
                'type' => 'tel',
                'name' => 'phone',
                'label' => 'Teléfono',
                'value' => $lastData['phone'] ?? '',
              ]) ?>
            </div>
            <div class="col-md-6">
              <?php Flight::render('components/inputs/input', [
                'type' => 'email',
                'name' => 'email',
                'label' => 'Correo electrónico',
                'value' => $lastData['email'] ?? '',
              ]) ?>
            </div>
            <div class="col-md-12">
              <?php Flight::render('components/inputs/textarea', [
                'name' => 'address',
                'label' => 'Dirección',
                'value' => $lastData['address'] ?? '',
              ]) ?>
            </div>
          </fieldset>
          <button class="btn btn-primary">Registrarme</button>
          <a href="./ingresar">¿Ya tienes cuenta?, inicia sesión</a>
        </div>
      </form>
    </div>
  </div>
</main>
