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
  <section class="white_box QA_section">
    <!-- <header class="list_header serach_field-area2 w-100">
      <form class="search_inner w-100">
        <input type="search" placeholder="Buscar por nombre...">
        <button>
          <i class="ti-search fs-2"></i>
        </button>
      </form>
    </header> -->
    <div class="QA_table table-responsive">
      <table class="table text-center">
        <thead>
          <tr>
            <th>#</th>
            <th>Nombre del departamento</th>
            <th>Pertenece a consulta externa</th>
            <th>Fecha de registro</th>
            <th>Estado</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($departments as $department) : ?>
            <tr>
              <form method="post" action="./departamentos/<?= $department->id ?>">
                <td><?= $department->id ?></td>
                <td class="p-0">
                  <input
                    placeholder="Nombre del departamento"
                    class="form-control"
                    required
                    name="name"
                    value="<?= $department->name ?>"
                  />
                </td>
                <td>
                  <?php if ($department->belongsToExternalConsultation): ?>
                    <span class="custom-badge status-green">Sí</span>
                  <?php else: ?>
                    <span class="custom-badge status-red">No</span>
                  <?php endif ?>
                </td>
                <td><?= $department->registeredDate ?></td>
                <td>
                  <?php if ($department->isActive()) : ?>
                    <a
                      href="./departamentos/<?= $department->id ?>/desactivar"
                      class="custom-badge status-green">
                      Activo
                    </a>
                  <?php else : ?>
                    <a
                      href="./departamentos/<?= $department->id ?>/activar"
                      class="custom-badge status-red">
                      Inactivo
                    </a>
                  <?php endif ?>
                </td>
                <td>
                  <button class="btn btn-primary text-white">Editar</button>
                </td>
              </form>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </section>
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
