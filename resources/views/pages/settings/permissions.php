<?php

declare(strict_types=1);

use App\OldModels\Department;
use App\OldModels\User;
use App\ValueObjects\Appointment;

/**
 * @var Department[] $departments
 * @var User[] $users
 * @var User $user
 */
$users ??= [];
assert(isset($departments) && is_array($departments), new Error('Departments not found'));
assert(isset($user) && $user instanceof User, new Error('User not found'));

?>

<section class="px-0 mb-4">
  <h2>Asignar departamentos</h2>
</section>

<?php if (!$users) : ?>
  No hay
  <?= $user->appointment === Appointment::Director ? 'coordinadores' : 'secretarios' ?>
  registrados
<?php else : ?>
  <section class="col-sm-5">
    <ul class="list-group my-4 roles-menu">
      <?php foreach ($users as $iteratedUser) : ?>
        <li>
          <a
            class="list-group-item d-flex justify-content-between"
            data-bs-toggle="list"
            href="#<?= $iteratedUser->idCard ?>">
            <strong class="me-2">v<?= $iteratedUser->idCard ?></strong>
            <span><?= $iteratedUser->getFullName() ?></span>
          </a>
        </li>
      <?php endforeach ?>
    </ul>
  </section>
  <section class="col-sm">
    <div class="tab-content">
      <?php foreach ($users as $iteratedUser) : ?>
        <form
          action="./configuracion/<?= $iteratedUser->id ?>/permisos"
          method="post"
          class="tab-pane fade text-center"
          id="<?= $iteratedUser->idCard ?>">
          <h4 class="mb-4">Departamentos asignados</h4>
          <div class="list-group">
            <?php foreach ($departments as $department) : ?>
              <label class="<?= ($department->isInactive() || !$user->hasDepartment($department)) ? 'pe-none opacity-50' : 'pe-auto' ?> list-group-item d-flex justify-content-between align-items-center" for="<?= $iteratedUser->idCard . $department->id . $department->name ?>">
                <span class="user-select-none"><?= $department->name ?></span>
                <div class="form-check form-switch">
                  <input
                    <?= !$department->isInactive() ?: 'disabled' ?>
                    name="<?= $department->id ?>"
                    class="form-check-input fs-3"
                    id="<?= $iteratedUser->idCard . $department->id . $department->name ?>"
                    type="checkbox" <?= !$iteratedUser->hasDepartment($department) ?: 'checked' ?> />
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
