<?php

use App\Models\Department;
use App\Models\User;
use App\ValueObjects\Appointment;

/**
 * @var array<int, Department> $departments
 * @var array<int, User> $users
 * @var ?string $error
 * @var ?string $message
 * @var User $user
 */

$loggedUser = $user;

?>

<section class="px-0 mb-4">
  <h2>Roles y permisos</h2>
</section>

<?php if (!$users) : ?>
  No hay
  <?= $loggedUser->appointment === Appointment::Director ? 'coordinadores' : 'secretarios' ?>
  registrados
<?php else : ?>
  <section class="col-sm-5">
    <ul class="list-group my-4 roles-menu">
      <?php foreach ($users as $user) : ?>
        <li>
          <a class="list-group-item d-flex justify-content-between" data-bs-toggle="list" href="#<?= $user->idCard ?>">
            <strong class="me-2">v<?= $user->idCard ?></strong>
            <span><?= $user->getFullName() ?></span>
          </a>
        </li>
      <?php endforeach ?>
    </ul>
  </section>
  <section class="col-sm">
    <div class="tab-content">
      <?php foreach ($users as $user) : ?>
        <form action="./configuracion/<?= $user->id ?>/permisos" method="post" class="tab-pane fade text-center" id="<?= $user->idCard ?>">
          <h4 class="mb-4">Departamentos asignados</h4>
          <div class="list-group">
            <?php foreach ($departments as $department) : ?>
              <label class="<?= ($department->isInactive() || !$loggedUser->hasDepartment($department)) ? 'pe-none opacity-50' : 'pe-auto' ?> list-group-item d-flex justify-content-between align-items-center" for="<?= $user->idCard . $department->id . $department->name ?>">
                <span class="user-select-none"><?= $department->name ?></span>
                <div class="form-check form-switch">
                  <input <?= $department->isInactive() ? 'disabled' : '' ?> name="<?= $department->id ?>" class="form-check-input fs-3" id="<?= $user->idCard . $department->id . $department->name ?>" type="checkbox" <?= $user->hasDepartment($department) ? 'checked' : '' ?> />
                </div>
              </label>
            <?php endforeach ?>
          </div>
          <button class="btn btn-secondary mt-3">Guardar cambios</button>
        </form>
      <?php endforeach ?>
    </div>
  </section>
<?php endif ?>
