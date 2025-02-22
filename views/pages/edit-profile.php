<?php

use App\Models\User;
use App\ValueObjects\Gender;
use App\ValueObjects\InstructionLevel;

/**
 * @var User $user
 * @var ?string $error
 * @var ?string $message
 */

?>

<section class="mb-4 d-flex px-0 align-items-center justify-content-between">
  <h2 class="m-0">Editar perfil</h2>
  <a href="./perfil" class="btn btn-primary rounded-pill d-flex align-items-center">
    <span class="px-2">Volver</span>
    <i class="px-2 ti-back-left"></i>
  </a>
</section>
<form enctype="multipart/form-data" method="post" class="d-flex px-0 flex-column align-items-center">
  <section class="white_box">
    <h3>Información básica</h3>
    <div class="row mt-4">
      <fieldset class="mt-4 mt-md-0 col-md row row-cols-md-2">
        <?php
          render('components/input-group', [
            'variant' => 'input',
            'type' => 'number',
            'name' => 'id_card',
            'value' => $user->idCard,
            'placeholder' => 'Cédula'
          ]);

          render('components/input-group', [
            'value' => $user->firstName,
            'name' => 'first_name',
            'placeholder' => 'Primer nombre',
            'type' => 'text',
            'variant' => 'input'
          ]);

          render('components/input-group', [
            'value' => $user->secondName,
            'name' => 'second_name',
            'placeholder' => 'Segundo nombre',
            'required' => false
          ]);

          render('components/input-group', [
            'value' => $user->firstLastName,
            'name' => 'first_last_name',
            'placeholder' => 'Primer apellido',
            'required' => true
          ]);

          render('components/input-group', [
            'value' => $user->secondLastName,
            'name' => 'second_last_name',
            'placeholder' => 'Segundo apellido',
            'required' => false
          ]);

          render('components/input-group', [
            'value' => $user->birthDate->getWithDashes(),
            'name' => 'birth_date',
            'placeholder' => 'Fecha de nacimiento',
            'type' => 'date',
            'required' => true
          ]);

          render('components/input-group', [
            'variant' => 'select',
            'name' => 'gender',
            'placeholder' => 'Género',
            'options' => array_map(fn(Gender $gender): array => [
              'value' => $gender->value,
              'text' => $gender->value,
              'selected' => $gender === $user->gender
            ], Gender::cases())
          ]);

          render('components/input-group', [
            'variant' => 'select',
            'name' => 'instruction_level',
            'placeholder' => 'Nivel de instrucción',
            'options' => array_map(fn(InstructionLevel $instruction): array => [
              'value' => $instruction->value,
              'text' => $instruction->getLongValue(),
              'selected' => $instruction === $user->instructionLevel
            ], InstructionLevel::cases())
          ]);

          render('components/input-group', [
            'variant' => 'file',
            'name' => 'profile_image',
            'placeholder' => 'Imagen de perfil',
            'cols' => 12,
            'required' => false
          ]);
        ?>
      </fieldset>
    </div>
  </section>
  <section class="white_box mt-4">
    <h3>Información de contacto</h3>
    <fieldset class="row mt-4">
      <?php
        render('components/input-group', [
          'variant' => 'textarea',
          'name' => 'address',
          'placeholder' => 'Dirección',
          'cols' => 12,
          'value' => $user->address,
          'required' => true
        ]);

        render('components/input-group', [
          'variant' => 'input',
          'type' => 'tel',
          'name' => 'phone',
          'value' => $user->phone,
          'placeholder' => 'Teléfono',
          'cols' => 6
        ]);

        render('components/input-group', [
          'type' => 'email',
          'name' => 'email',
          'value' => $user->email->asString(),
          'placeholder' => 'Correo'
        ]);
      ?>
    </fieldset>
  </section>
  <button class="btn btn-primary btn-lg mt-4 px-5 rounded-pill">Guardar</button>
</form>
