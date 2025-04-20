<?php

declare(strict_types=1);

use HAJU\ValueObjects\Gender;

$id ??= 'registrar';
$action ??= "./pacientes#$id";

?>

<div class="modal fade" id="<?= $id ?>">
  <div class="modal-dialog modal-dialog-scrollable">
    <form action="<?= $action ?>" class="modal-content" method="post">
      <header class="modal-header">
        <h3 class="modal-title fs-5">Registrar paciente</h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </header>
      <section class="modal-body">
        <fieldset class="row">
          <summary class="fs-6 mb-2">Datos personales</summary>
          <?php

          Flight::render('components/input-group', [
            'name' => 'first_name',
            'placeholder' => 'Primer nombre',
            'cols' => 6,
          ]);

          Flight::render('components/input-group', [
            'name' => 'second_name',
            'placeholder' => 'Segundo nombre',
            'required' => false,
            'cols' => 6,
          ]);

          Flight::render('components/input-group', [
            'name' => 'first_last_name',
            'placeholder' => 'Primer apellido',
            'cols' => 6,
          ]);

          Flight::render('components/input-group', [
            'name' => 'second_last_name',
            'placeholder' => 'Segundo apellido',
            'required' => false,
            'cols' => 6,
          ]);

          Flight::render('components/input-group', [
            'type' => 'number',
            'name' => 'id_card',
            'placeholder' => 'Cédula',
            'cols' => 6,
          ]);

          Flight::render('components/input-group', [
            'type' => 'date',
            'name' => 'birth_date',
            'placeholder' => 'Fecha de nacimiento',
            'cols' => 6,
          ]);

          Flight::render('components/input-group', [
            'type' => 'select',
            'name' => 'gender',
            'placeholder' => 'Género',
            'options' => array_map(fn (Gender $gender): array => [
              'value' => $gender->value,
              'text' => $gender->value
            ], Gender::cases()),
          ]);

          ?>
        </fieldset>
      </section>
      <footer class="modal-footer">
        <button class="btn btn-primary">Registrar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Cancelar
        </button>
      </footer>
    </form>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    if (location.href.endsWith('#<?= $id ?>')) {
      new bootstrap.Modal('#<?= $id ?>').show()
    }
  })
</script>
