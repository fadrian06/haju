<?php

use App\Models\Department;
use App\Models\User;

/**
 * @var array<int, Department> $departments
 * @var User $user
 */

?>

<section class="mb-4 d-flex px-0 align-items-center justify-content-between">
  <h2>Departamentos</h2>
  <a href="<?= route('/departamentos/registrar') ?>" class="btn btn-primary rounded-pill d-flex align-items-center">
    <i class="px-2 ti-plus"></i>
    <span class="px-2">Añadir departamento</span>
  </a>
</section>

<section class="white_box QA_section">
  <header class="list_header serach_field-area2 w-100">
    <form class="search_inner w-100">
      <input type="search" placeholder="Buscar por nombre...">
      <button>
        <i class="ti-search fs-2"></i>
      </button>
    </form>
  </header>
  <div class="QA_table table-responsive">
    <table class="table text-center table-hover">
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
            <td><?= $department->getId() ?></td>
            <td><?= $department->name ?></td>
            <td><?= $department->registered->format('d/m/Y') ?></td>
            <td>
              <?php if (/*$department->isActive*/rand(0, 1)) : ?>
                <span class="custom-badge status-green">Activo</span>
              <?php else : ?>
                <span class="custom-badge status-red">Inactivo</span>
              <?php endif ?>
            </td>
            <td>
              <a class="btn btn-primary text-white" href="<?= route('/departamentos/@id/editar', ['id' => $department->getId()]) ?>">
                Editar
              </a>
            </td>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>
</section>
