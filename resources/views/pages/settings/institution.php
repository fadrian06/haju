<?php

declare(strict_types=1);

use HAJU\Models\Hospital;

/**
 * @var Hospital $hospital
 * @var ?string $message
 * @var ?string $error
 */

?>

<section class="mb-4 d-inline-flex px-0 align-items-center justify-content-between">
  <h2>Configuración de la institución</h2>
</section>

<form method="post" class="white_box d-flex flex-column align-items-center">
  <fieldset class="row w-100">
    <?php

    Flight::render('components/input-group', [
      'value' => $hospital->name,
      'name' => 'name',
      'placeholder' => 'Nombre de la institución',
    ]);

    Flight::render('components/input-group', [
      'value' => $hospital->asic,
      'name' => 'asic',
      'placeholder' => 'ASIC'
    ]);

    Flight::render('components/input-group', [
      'value' => $hospital->type,
      'name' => 'type',
      'placeholder' => 'Tipo'
    ]);

    Flight::render('components/input-group', [
      'value' => $hospital->place,
      'name' => 'place',
      'placeholder' => 'Lugar'
    ]);

    Flight::render('components/input-group', [
      'value' => $hospital->municipality,
      'name' => 'municipality',
      'placeholder' => 'Municipio'
    ]);

    Flight::render('components/input-group', [
      'value' => $hospital->parish,
      'name' => 'parish',
      'placeholder' => 'Parroquia'
    ]);

    Flight::render('components/input-group', [
      'value' => $hospital->region,
      'name' => 'region',
      'placeholder' => 'Región'
    ]);

    Flight::render('components/input-group', [
      'value' => $hospital->healthDepartment,
      'name' => 'health_department',
      'placeholder' => 'Departamento de salud'
    ]);

    ?>
  </fieldset>
  <button class="btn btn-primary btn-lg mt-4 px-5 rounded-pill">Guardar</button>
</form>
