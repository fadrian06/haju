<?php

use App\Models\Department;
use App\Models\User;

/**
 * @var array<int, Department> $departments
 * @var User $user
 * @var ?string $error
 * @var ?string $message
 */

?>

<section class="mb-4 d-sm-flex px-0 align-items-center justify-content-between">
  <h2>Departamentos</h2>
  <!-- <a data-bs-toggle="modal" href="#registrar" class="btn btn-primary rounded-pill d-flex align-items-center">
    <i class="px-2 ti-plus"></i>
    <span class="px-2">Añadir departamento</span>
  </a> -->
</section>

<?php if ($departments !== []) : ?>
  <ul class="list-unstyled row row-cols-sm-2 row-cols-md-3">
    <?php foreach ($departments as $department) : ?>
      <li class="mb-4 d-flex align-items-stretch">
        <article class="card card-body text-center">
          <div class="dropdown position-relative">
            <button style="right: 0" class="bg-transparent border-0 position-absolute" data-bs-toggle="dropdown">
              <i class="ti-more"></i>
            </button>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="./departamentos/<?= $department->id ?>/<?= $department->isActive() ? 'desactivar' : 'activar' ?>">
                <i class="ti-<?= $department->isActive() ? 'un' : '' ?>lock"></i>
                <?= $department->isActive() ? 'Desactivar' : 'Activar' ?>
              </a>
            </div>
          </div>
          <picture class="p-3">
            <img class="img-fluid" src="<?= urldecode($department->iconFilePath->asString()) ?>" style="height: 130px; object-fit: contain;" title="<?= $department->name ?>" />
          </picture>
          <span class="custom-badge status-<?= $department->isActive() ? 'green' : 'red' ?> mx-4 mb-2">
            <?= $department->isActive() ? 'Activo' : 'Inactivo' ?>
          </span>
          <h4><?= $department->name ?></h4>
          <small class="text-muted">
            Fecha de registro: <?= $department->registeredDate ?>
          </small>
        </article>
      </li>
    <?php endforeach ?>
  </ul>
<?php endif ?>

<!-- <div class="modal fade" id="registrar">
  <div class="modal-dialog">
    <form action="./departamentos#registrar" enctype="multipart/form-data" class="modal-content" method="post">
      <header class="modal-header">
        <h3 class="modal-title fs-5">Añadir departamento</h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </header>
      <section class="modal-body">
        <?php
        render('components/input-group', [
          'name' => 'name',
          'placeholder' => 'Nombre del departamento',
          'cols' => 12
        ]);

        render('components/input-group', [
          'variant' => 'file',
          'name' => 'department_icon',
          'placeholder' => 'Icono'
        ]);

        render('components/input-group', [
          'variant' => 'checkbox',
          'name' => 'belongs_to_external_consultation',
          'placeholder' => 'Pertenece a Consulta Externa'
        ]);

        render('components/input-group', [
          'variant' => 'checkbox',
          'name' => 'is_active',
          'placeholder' => 'Estado <small>(activo/inactivo)</small>',
          'checked' => true
        ]);
        ?>
      </section>
      <footer class="modal-footer">
        <button class="btn btn-primary">Añadir</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Cancelar
        </button>
      </footer>
    </form>
  </div>
</div> -->

<script>
  document.addEventListener('DOMContentLoaded', () => {
    if (location.href.endsWith('#registrar')) {
      new bootstrap.Modal('#registrar').show()
    }
  })
</script>
