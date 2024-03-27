<?php

use App\Models\Department;
use App\Models\User;

/**
 * @var array<int, Department> $departments
 * @var array<int, User> $users
 * @var ?string $error
 * @var ?string $message
 */

?>

<section class="px-0 mb-4">
  <h2>Roles y permisos</h2>
</section>

<section class="col-sm-5">
  <ul class="list-group my-4 roles-menu">
    <?php foreach ($users as $user) : ?>
      <li>
        <a
          class="list-group-item d-flex justify-content-between"
          data-bs-toggle="list"
          href="#<?= $user->getIdCard() ?>">
          <strong class="me-2">v<?= $user->getIdCard() ?></strong>
          <span><?= $user->getFullName() ?></span>
        </a>
      </li>
    <?php endforeach ?>
  </ul>
</section>
<section class="col-sm">
  <?php $message && render('components/notification', ['type' => 'message', 'text' => $message]) ?>
  <div class="tab-content">
    <?php foreach ($users as $user) : ?>
      <form
        action="./configuracion/<?= $user->getId() ?>/permisos"
        method="post"
        class="tab-pane fade text-center"
        id="<?= $user->getIdCard() ?>">
        <h4 class="mb-4">Departamentos asignados</h4>
        <div class="list-group">
          <?php foreach ($departments as $department) : ?>
            <label
              class="<?= $department->isInactive() ? 'pe-none opacity-50' : 'pe-auto' ?> list-group-item d-flex justify-content-between align-items-center"
              for="<?= $user->getIdCard() . $department->getId() . $department->getName() ?>">
              <span class="user-select-none"><?= $department->getName() ?></span>
              <div class="form-check form-switch">
                <input
                  <?= $department->isInactive() ? 'disabled' : '' ?>
                  name="<?= $department->getId() ?>"
                  class="form-check-input fs-3"
                  id="<?= $user->getIdCard() . $department->getId() . $department->getName() ?>"
                  type="checkbox"
                  <?= $user->hasDepartment($department) ? 'checked' : '' ?>
                />
              </div>
            </label>
          <?php endforeach ?>
        </div>
        <button class="btn btn-primary mt-3">Guardar cambios</button>
      </form>
    <?php endforeach ?>
  </div>
</section>
