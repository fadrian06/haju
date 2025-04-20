<?php

declare(strict_types=1);

use HAJU\Enums\Gender;
use HAJU\Enums\InstructionLevel;
use flight\Container;
use Leaf\Http\Session;

$session = Container::getInstance()->get(Session::class);

?>

<main class="container bg-white rounded-3 my-4 py-4 d-flex flex-column justify-content-center">
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
                'value' => $session->get('lastData', [])['first_name'] ?? '',
              ]) ?>
            </div>
            <div class="col-md-6">
              <?php Flight::render('components/inputs/input', [
                'name' => 'second_name',
                'label' => 'Segundo nombre',
                'required' => false,
                'value' => $session->get('lastData', [])['second_name'] ?? '',
              ]) ?>
            </div>
            <div class="col-md-6">
              <?php Flight::render('components/inputs/input', [
                'name' => 'first_last_name',
                'label' => 'Primer apellido',
                'value' => $session->get('lastData', [])['first_last_name'] ?? '',
              ]) ?>
            </div>
            <div class="col-md-6">
              <?php Flight::render('components/inputs/input', [
                'name' => 'second_last_name',
                'label' => 'Segundo apellido',
                'required' => false,
                'value' => $session->get('lastData', [])['second_last_name'] ?? '',
              ]) ?>
            </div>
            <div class="col-md-6">
              <?php Flight::render('components/inputs/input', [
                'type' => 'date',
                'name' => 'birth_date',
                'label' => 'Fecha de nacimiento',
                'value' => $session->get('lastData', [])['birth_date'] ?? '',
              ]) ?>
            </div>
            <div class="col-md-6">
              <?php Flight::render('components/inputs/select', [
                'name' => 'gender',
                'options' => array_map(static fn(Gender $gender): array => [
                  'slot' => $gender->value,
                  'value' => $gender->value,
                  'selected' => $gender->value === ($session->get('lastData', [])['gender'] ?? ''),
                ], Gender::cases()),
                'label' => 'Género',
              ]) ?>
            </div>
            <div class="col-md-6">
              <?php Flight::render('components/inputs/select', [
                'name' => 'instruction_level',
                'options' => array_map(static fn(InstructionLevel $instruction): array => [
                  'slot' => $instruction->getLongValue(),
                  'value' => $instruction->value,
                  'selected' => $instruction->value === ($session->get('lastData', [])['instruction_level'] ?? ''),
                ], InstructionLevel::cases()),
                'label' => 'Nivel de instrucción',
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
            x-data="{ password: '' }"
          >
            <legend class="h5 mb-2 text-start">Credenciales de ingreso</legend>
            <div class="col-md-12">
              <?php Flight::render('components/inputs/input', [
                'type' => 'number',
                'name' => 'id_card',
                'label' => 'Cédula',
                'value' => $session->get('lastData', [])['id_card'] ?? '',
              ]) ?>
            </div>
            <div class="col-md-6">
              <?php Flight::render('components/inputs/input-password', [
                'name' => 'password',
                'label' => 'Contraseña',
                'value' => $session->get('lastData', [])['password'] ?? '',
                'model' => 'password',
              ]) ?>
            </div>
            <div class="col-md-6">
              <?php Flight::render('components/inputs/input-password', [
                'name' => 'confirm_password',
                'label' => 'Confirmar contraseña',
                'value' => $session->get('lastData', [])['confirm_password'] ?? '',
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
                'value' => $session->get('lastData', [])['phone'] ?? '',
              ]) ?>
            </div>
            <div class="col-md-6">
              <?php Flight::render('components/inputs/input', [
                'type' => 'email',
                'name' => 'email',
                'label' => 'Correo electrónico',
                'value' => $session->get('lastData', [])['email'] ?? '',
              ]) ?>
            </div>
            <div class="col-md-12">
              <?php Flight::render('components/inputs/textarea', [
                'name' => 'address',
                'label' => 'Dirección',
                'value' => $session->get('lastData', [])['address'] ?? '',
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
