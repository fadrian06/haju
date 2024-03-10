<?php

use App\Models\Department;
use App\Models\User;

/**
 * @var array<int, Department> $departments
 * @var array<int, User> $users
 */

?>

<section class="px-0 mb-4">
  <h2>Roles y permisos</h2>
</section>

<section class="col-sm-5">
  <ul class="list-group my-4 roles-menu">
    <?php foreach ($users as $user) : ?>
      <li>
        <a class="list-group-item d-flex justify-content-between" data-bs-toggle="list" href="#<?= $user->idCard ?>">
          <strong>v<?= $user->idCard ?></strong>
          <span><?= $user->getFullName() ?></span>
        </a>
      </li>
    <?php endforeach ?>
  </ul>
</section>
<section class="col-sm">
  <h4 class="mb-4">Departamentos asignados</h4>
  <div class="tab-content">
    <?php foreach ($users as $user) : ?>
      <form action="<?= route('/configuracion/@id/permisos', ['id' => $user->getId()]) ?>" method="post" class="tab-pane fade text-center" id="<?= $user->idCard ?>">
        <div class="list-group">
          <?php foreach ($departments as $department) : ?>
            <label style="cursor: <?= $department->isActive ? 'pointer' : '' ?>" class="<?= !$department->isActive ? 'disabled' : '' ?> list-group-item d-flex justify-content-between align-items-center" for="<?= $user->idCard . $department->getId() . $department->name ?>">
              <?= $department->name ?>
              <div class="form-check form-switch">
                <input <?= !$department->isActive ? 'disabled' : '' ?> name="<?= $department->getId() ?>" style="cursor: inherit" class="form-check-input fs-3" id="<?= $user->idCard . $department->getId() . $department->name ?>" type="checkbox" <?= $user->hasDepartment($department) ? 'checked' : '' ?> />
              </div>
            </label>
          <?php endforeach ?>
        </div>
        <button class="btn btn-primary mt-3">Guardar cambios</button>
      </form>
    <?php endforeach ?>
  </div>
</section>
