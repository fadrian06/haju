<?php

use App\Models\Patient;

/** @var Patient[] $patients */

?>

<section class="mb-4 d-inline-flex px-0 align-items-center justify-content-between">
  <h2>Registrar hospitalización</h2>
</section>

<form action="./hospitalizaciones" method="post" class="white_box d-flex flex-column align-items-center">
  <fieldset class="row w-100">
    <?php

    render('components/input-group', [
      'variant' => 'select',
      'options' => array_map(fn (Patient $patient): array => [
        'value' => $patient->id,
        'text' => "v-{$patient->idCard} ~ {$patient->getFullName()}"
      ], $patients),
      'placeholder' => 'Buscar cédula...',
      'name' => 'id_card',
      'cols' => 7
    ]);

    ?>

    <div class="col d-flex flex-column text-center mb-4 mb-md-0">
      ¿El paciente no está registrado?
      <div class="mt-2">
        <a href="#registrar" data-bs-toggle="modal" class="btn btn-secondary btn-sm">
          Regístralo
        </a>
      </div>
    </div>

    <?php



    ?>
  </fieldset>
</form>
