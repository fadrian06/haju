<?php

use App\Models\Gender;
use App\Models\InstructionLevel;

/** @var ?string $error */

?>

<div class="col-md-8 mx-auto">
  <section class="px-0 modal modal-content cs_modal">
    <header class="modal-header px-4 py-3">
      <h5>Regístrate</h5>
    </header>
    <form enctype="multipart/form-data" class="modal-body" method="post">
      <?= $error &&  render('components/notification', ['type' => 'error', 'text' => $error]) ?>
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
            'placeholder' => 'Cédula'
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
      <?php render('components/input-group', [
        'variant' => 'file',
        'name' => 'profile_image',
        'placeholder' => 'Foto de perfil'
      ]) ?>
      <button class="btn_1">Registrarse</button>
    </form>
  </section>
</div>
