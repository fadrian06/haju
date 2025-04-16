<?php

declare(strict_types=1);

use App\Models\Doctor;
use App\ValueObjects\Gender;

/** @var Doctor $doctor */
assert(isset($doctor) && $doctor instanceof Doctor, new Error('Doctor not found'));

?>

<section class="mb-4 d-inline-flex px-0 align-items-center justify-content-between">
  <h2>Editar doctor</h2>
</section>

<form method="post" class="white_box d-flex flex-column align-items-center">
  <fieldset class="row w-100">
    <?php

    Flight::render('components/input-group', [
      'name' => 'first_name',
      'placeholder' => 'Primer nombre',
      'cols' => 6,
      'value' => $doctor->firstName,
    ]);

    Flight::render('components/input-group', [
      'name' => 'second_name',
      'placeholder' => 'Segundo nombre',
      'required' => false,
      'value' => $doctor->secondName,
      'cols' => 6,
    ]);

    Flight::render('components/input-group', [
      'name' => 'first_last_name',
      'placeholder' => 'Primer apellido',
      'value' => $doctor->firstLastName,
      'cols' => 6,
    ]);

    Flight::render('components/input-group', [
      'name' => 'second_last_name',
      'placeholder' => 'Segundo apellido',
      'required' => false,
      'value' => $doctor->secondLastName,
      'cols' => 6,
    ]);

    Flight::render('components/input-group', [
      'type' => 'number',
      'name' => 'id_card',
      'placeholder' => 'Cédula',
      'value' => $doctor->idCard,
      'cols' => 6,
    ]);

    Flight::render('components/input-group', [
      'type' => 'date',
      'name' => 'birth_date',
      'placeholder' => 'Fecha de nacimiento',
      'value' => $doctor->birthDate->getWithDashes(),
      'cols' => 6,
    ]);

    Flight::render('components/input-group', [
      'type' => 'select',
      'name' => 'gender',
      'placeholder' => 'Género',
      'options' => array_map(static fn(Gender $gender): array => [
        'value' => $gender->value,
        'text' => $gender->value,
        'selected' => $doctor->gender === $gender
      ], Gender::cases()),
    ]);

    ?>
  </fieldset>
  <button class="btn btn-primary btn-lg mt-4 px-5 rounded-pill">Actualizar</button>
</form>
