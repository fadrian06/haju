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

<section class="mb-4 d-md-flex px-0 align-items-center justify-content-between">
  <h2>Departamentos</h2>
  <a data-bs-toggle="modal" href="#registrar" class="btn btn-primary rounded-pill d-flex align-items-center">
    <i class="px-2 ti-plus"></i>
    <span class="px-2">Añadir departamento</span>
  </a>
</section>

<?php if ($departments !== []): ?>
  <section class="white_box QA_section">
    <!-- <header class="list_header serach_field-area2 w-100">
      <form class="search_inner w-100">
        <input type="search" placeholder="Buscar por nombre...">
        <button>
          <i class="ti-search fs-2"></i>
        </button>
      </form>
    </header> -->
    <?php if ($error) : ?>
      <div class="alert alert-danger alert-dismissible fade show">
        <?= $error ?>
        <button class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php elseif ($message) : ?>
      <div class="alert alert-info alert-dismissible fade show">
        <?= $message ?>
        <button class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif ?>
    <div class="QA_table table-responsive">
      <table class="table text-center">
        <thead>
          <tr>
            <th>#</th>
            <th>Nombre del departamento</th>
            <th>Fecha de registro</th>
            <th>Estado</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($departments as $department) : ?>
            <tr>
              <form method="post" action="<?= route('/departamentos/@id', ['id' => $department->getId()]) ?>">
                <td><?= $department->getId() ?></td>
                <td class="p-0">
                  <input placeholder="Nombre del departamento" class="form-control" required name="name" value="<?= $department->name ?>" />
                </td>
                <td><?= $department->getRegisteredDate() ?></td>
                <td>
                  <?php if ($department->isActive) : ?>
                    <a href="<?= route('/departamentos/@id/desactivar', ['id' => $department->getId()]) ?>" class="custom-badge status-green">
                      Activo
                    </a>
                  <?php else : ?>
                    <a href="<?= route('/departamentos/@id/activar', ['id' => $department->getId()]) ?>" class="custom-badge status-red">
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

<div class="modal fade" id="registrar">
  <div class="modal-dialog">
    <form class="modal-content" method="post">
      <header class="modal-header">
        <h3 class="modal-title fs-5">Añadir departamento</h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </header>
      <section class="modal-body">
        <div class="form-floating mb-4">
          <input class="form-control" name="name" required id="name" placeholder="Nombre del departamento" />
          <label for="name">Nombre del departamento</label>
        </div>
        <div class="form-check form-switch fs-6">
          <input class="form-check-input" name="is_active" type="checkbox" id="is_active" checked />
          <label class="form-check-label" for="is_active">
            Estado <small>(activo/inactivo)</small>
          </label>
        </div>
      </section>
      <footer class="modal-footer">
        <button class="btn btn-primary">Añadir</button>
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
