<?php

use App\Models\Doctor;
use App\Models\User;
use App\ValueObjects\Gender;

$doctors = array_map(fn (Doctor $doctor) => $doctor, $doctors);
$loggedUser = $user;
assert($loggedUser instanceof User);

?>

<section class="mb-4 d-inline-flex px-0 align-items-center justify-content-between">
  <h2>Doctores</h2>
  <a data-bs-toggle="modal" href="#registrar" class="btn btn-primary rounded-pill d-flex align-items-center">
    <i class="px-2 ti-plus"></i>
    <span class="px-2">Registrar doctor</span>
  </a>
</section>

<?php if (!$doctors): ?>
  No hay doctores registrados
<?php endif ?>

<ul class="list-unstyled row row-cols-sm-2 row-cols-md-3">
  <?php foreach ($doctors as $doctor) : ?>
    <li class="mb-4 d-flex align-items-stretch">
      <article class="card card-body text-center <?= $doctor->canBeEditedBy($loggedUser) ? '' : 'pe-none opacity-50 user-select-none' ?>">
        <div class="dropdown position-relative">
          <button style="right: 0" class="bg-transparent border-0 position-absolute" data-bs-toggle="dropdown">
            <i class="ti-more"></i>
          </button>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="./doctores/<?= $doctor->idCard ?>">
              <i class="ti-pencil"></i>
              Editar
            </a>
          </div>
        </div>
        <picture class="p-3">
          <img class="img-fluid rounded-circle" src="./assets/img/client_img.png" style="height: 130px" title="<?= $doctor->getFullName() ?>" />
        </picture>
        <h4 title="<?= $doctor->getFullName() ?>">
          <?= "{$doctor->firstName} {$doctor->firstLastName}" ?>
        </h4>
        <small class="text-muted" title="<?= $doctor->registeredBy->getFullName() ?>">
          Registrado por: <?= $doctor->registeredBy->firstName ?>
        </small>
      </article>
    </li>
  <?php endforeach ?>
</ul>

<div class="modal fade" id="registrar">
  <div class="modal-dialog modal-dialog-scrollable">
    <form class="modal-content" method="post">
      <header class="modal-header">
        <h3 class="modal-title fs-5">Registrar paciente</h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </header>
      <section class="modal-body">
        <fieldset class="row">
          <summary class="fs-6 mb-2">Datos personales</summary>
          <?php

          render('components/input-group', [
            'name' => 'first_name',
            'placeholder' => 'Primer nombre',
            'cols' => 6,
            'variant' => 'input'
          ]);

          render('components/input-group', [
            'name' => 'second_name',
            'placeholder' => 'Segundo nombre',
            'required' => false
          ]);

          render('components/input-group', [
            'name' => 'first_last_name',
            'placeholder' => 'Primer apellido',
            'required' => true
          ]);

          render('components/input-group', [
            'name' => 'second_last_name',
            'placeholder' => 'Segundo apellido',
            'required' => false
          ]);

          render('components/input-group', [
            'type' => 'number',
            'name' => 'id_card',
            'placeholder' => 'Cédula',
            'required' => true
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
            'options' => array_map(fn (Gender $gender): array => [
              'value' => $gender->value,
              'text' => $gender->value
            ], Gender::cases()),
            'cols' => 12
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
    if (location.href.endsWith('#registrar')) {
      new bootstrap.Modal('#registrar').show()
    }
  })
</script>
