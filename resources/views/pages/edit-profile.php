<?php

declare(strict_types=1);

use App\OldModels\User;
use App\ValueObjects\Gender;
use App\ValueObjects\InstructionLevel;

/**
 * @var User $user
 */
assert(isset($user) && $user instanceof User, new Error('User not set'));

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

        Flight::render('components/input-group', [
          'type' => 'number',
          'name' => 'id_card',
          'value' => $user->idCard,
          'placeholder' => 'Cédula',
          'cols' => 6,
        ]);

        Flight::render('components/input-group', [
          'value' => $user->firstName,
          'name' => 'first_name',
          'placeholder' => 'Primer nombre',
          'cols' => 6,
        ]);

        Flight::render('components/input-group', [
          'value' => $user->secondName,
          'name' => 'second_name',
          'placeholder' => 'Segundo nombre',
          'required' => false,
          'cols' => 6,
        ]);

        Flight::render('components/input-group', [
          'value' => $user->firstLastName,
          'name' => 'first_last_name',
          'placeholder' => 'Primer apellido',
          'cols' => 6,
        ]);

        Flight::render('components/input-group', [
          'value' => $user->secondLastName,
          'name' => 'second_last_name',
          'placeholder' => 'Segundo apellido',
          'required' => false,
          'cols' => 6,
        ]);

        Flight::render('components/input-group', [
          'value' => $user->birthDate->getWithDashes(),
          'name' => 'birth_date',
          'placeholder' => 'Fecha de nacimiento',
          'type' => 'date',
          'cols' => 6,
        ]);

        Flight::render('components/input-group', [
          'type' => 'select',
          'name' => 'gender',
          'placeholder' => 'Género',
          'options' => array_map(fn(Gender $gender): array => [
            'value' => $gender->value,
            'text' => $gender->value,
            'selected' => $gender === $user->gender
          ], Gender::cases()),
          'cols' => 6,
        ]);

        Flight::render('components/input-group', [
          'type' => 'select',
          'name' => 'instruction_level',
          'placeholder' => 'Nivel de instrucción',
          'options' => array_map(fn(InstructionLevel $instruction): array => [
            'value' => $instruction->value,
            'text' => $instruction->getLongValue(),
            'selected' => $instruction === $user->instructionLevel
          ], InstructionLevel::cases()),
          'cols' => 6,
        ]);

        Flight::render('components/input-group', [
          'type' => 'file',
          'name' => 'profile_image',
          'placeholder' => 'Imagen de perfil',
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

      Flight::render('components/input-group', [
        'type' => 'textarea',
        'name' => 'address',
        'placeholder' => 'Dirección',
        'value' => $user->address,
      ]);

      Flight::render('components/input-group', [
        'type' => 'tel',
        'name' => 'phone',
        'value' => $user->phone,
        'placeholder' => 'Teléfono',
        'cols' => 6,
      ]);

      Flight::render('components/input-group', [
        'type' => 'email',
        'name' => 'email',
        'value' => $user->email->asString(),
        'placeholder' => 'Correo',
        'cols' => 6,
      ]);

      ?>
    </fieldset>
  </section>
  <button class="btn btn-primary btn-lg mt-4 px-5 rounded-pill">Guardar</button>
</form>
