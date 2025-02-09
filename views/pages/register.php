<?php

use App\ValueObjects\Gender;
use App\ValueObjects\InstructionLevel;
use Leaf\Http\Session;

?>

<div class="col-md-9 mx-auto">
  <section class="px-0 modal modal-content cs_modal">
    <header class="modal-header px-4 py-3">
      <h5>Regístrate</h5>
    </header>
    <form
      enctype="multipart/form-data"
      class="modal-body"
      method="post"
      novalidate>
      <fieldset class="row">
        <summary class="fs-6 mb-2">Datos personales</summary>
        <?php
          render('components/input-group', [
            'name' => 'first_name',
            'placeholder' => 'Primer nombre',
            'value' => Session::get('lastData', [])['first_name'] ?? ''
          ]);

          render('components/input-group', [
            'name' => 'second_name',
            'placeholder' => 'Segundo nombre',
            'required' => false,
            'value' => Session::get('lastData', [])['second_name'] ?? ''
          ]);

          render('components/input-group', [
            'name' => 'first_last_name',
            'placeholder' => 'Primer apellido',
            'required' => true,
            'value' => Session::get('lastData', [])['first_last_name'] ?? ''
          ]);

          render('components/input-group', [
            'name' => 'second_last_name',
            'placeholder' => 'Segundo apellido',
            'required' => false,
            'value' => Session::get('lastData', [])['second_last_name'] ?? ''
          ]);

          render('components/input-group', [
            'type' => 'number',
            'name' => 'id_card',
            'placeholder' => 'Cédula',
            'required' => true,
            'value' => Session::get('lastData', [])['id_card'] ?? ''
          ]);

          render('components/input-group', [
            'type' => 'date',
            'name' => 'birth_date',
            'placeholder' => 'Fecha de nacimiento',
            'value' => Session::get('lastData', [])['birth_date'] ?? ''
          ]);

          render('components/input-group', [
            'variant' => 'select',
            'name' => 'gender',
            'placeholder' => 'Género',
            'options' => array_map(function (Gender $gender): array {
              return [
                'value' => $gender->value,
                'text' => $gender->value,
                'selected' => $gender->value === (Session::get('lastData', [])['gender'] ?? '')
              ];
            }, Gender::cases())
          ]);

          render('components/input-group', [
            'variant' => 'select',
            'name' => 'instruction_level',
            'placeholder' => 'Nivel de instrucción',
            'options' => array_map(function (InstructionLevel $instruction): array {
              return [
                'value' => $instruction->value,
                'text' => $instruction->getLongValue(),
                'selected' => $instruction->value === (Session::get('lastData', [])['instruction_level'] ?? '')
              ];
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
            'placeholder' => 'Contraseña',
            'value' => Session::get('lastData', [])['password'] ?? ''
          ]);

          render('components/input-group', [
            'variant' => 'input',
            'type' => 'password',
            'name' => 'confirm_password',
            'placeholder' => 'Confirmar contraseña',
            'value' => Session::get('lastData', [])['confirm_password'] ?? ''
          ]);
        ?>
      </fieldset>
      <fieldset class="row">
        <summary class="fs-6 mb-2">Datos de contacto</summary>
        <?php
          render('components/input-group', [
            'type' => 'tel',
            'name' => 'phone',
            'placeholder' => 'Teléfono',
            'value' => Session::get('lastData', [])['phone'] ?? ''
          ]);

          render('components/input-group', [
            'type' => 'email',
            'name' => 'email',
            'placeholder' => 'Correo electrónico',
            'value' => Session::get('lastData', [])['email'] ?? ''
          ]);

          render('components/input-group', [
            'variant' => 'textarea',
            'name' => 'address',
            'placeholder' => 'Dirección',
            'cols' => 12,
            'value' => Session::get('lastData', [])['address'] ?? ''
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
