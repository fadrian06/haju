<?php

use App\Models\Doctor;
use App\ValueObjects\Gender;

assert($doctor instanceof Doctor);

?>

<section class="mb-4 d-inline-flex px-0 align-items-center justify-content-between">
  <h2>Editar doctor</h2>
</section>

<form method="post" class="white_box d-flex flex-column align-items-center">
  <fieldset class="row w-100">
    <?php

      render('components/input-group', [
        'name' => 'first_name',
        'placeholder' => 'Primer nombre',
        'cols' => 6,
        'variant' => 'input',
        'value' => $doctor->firstName
      ]);

      render('components/input-group', [
        'name' => 'second_name',
        'placeholder' => 'Segundo nombre',
        'required' => false,
        'value' => $doctor->secondName
      ]);

      render('components/input-group', [
        'name' => 'first_last_name',
        'placeholder' => 'Primer apellido',
        'required' => true,
        'value' => $doctor->firstLastName
      ]);

      render('components/input-group', [
        'name' => 'second_last_name',
        'placeholder' => 'Segundo apellido',
        'required' => false,
        'value' => $doctor->secondLastName
      ]);

      render('components/input-group', [
        'type' => 'number',
        'name' => 'id_card',
        'placeholder' => 'Cédula',
        'required' => true,
        'value' => $doctor->idCard
      ]);

      render('components/input-group', [
        'type' => 'date',
        'name' => 'birth_date',
        'placeholder' => 'Fecha de nacimiento',
        'value' => $doctor->birthDate->getWithDashes()
      ]);

      render('components/input-group', [
        'variant' => 'select',
        'name' => 'gender',
        'placeholder' => 'Género',
        'options' => array_map(fn (Gender $gender): array => [
          'value' => $gender->value,
          'text' => $gender->value,
          'selected' => $doctor->gender === $gender
        ], Gender::cases()),
        'cols' => 12
      ]);

    ?>
  </fieldset>
  <button class="btn btn-primary btn-lg mt-4 px-5 rounded-pill">Actualizar</button>
</form>
