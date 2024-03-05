<?php
  /** @var array<int, App\Models\Department> $departments */
?>

<table class="table table-bordered table-striped">
  <caption align="top" class="h3">Departamentos</caption>
  <thead>
    <tr>
      <th>ID</th>
      <th>Nombre</th>
      <th>Fecha de registro</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($departments as $department): ?>
      <tr>
        <td><?= $department->getId() ?></td>
        <td><?= $department->name ?></td>
        <td><?= $department->registered->format('Y/m/d') ?></td>
        <td class="d-flex align-items-center">
          <a href="#" class="ti-pencil-alt fs-4" title="Editar"></a>
          <div class="form-check form-switch fs-4 ms-2">
            <input type="checkbox" class="form-check-input" />
          </div>
        </td>
      </tr>
    <?php endforeach ?>
  </tbody>

</table>
