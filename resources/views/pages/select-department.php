<?php

declare(strict_types=1);

use App\Models\User;

/** @var User $user */
assert(isset($user) && $user instanceof User, new Error('User not set'));

?>

<main class="container bg-white rounded-3 my-4 py-4">
  <div class="row justify-content-center">
    <div class="col-12 px-md-5 text-center">
      <h1 class="mb-4">Seleccione un departamento</h1>
      <div class="row row-gap-4">
        <?php foreach ($user->getDepartment() as $department): ?>
          <a
            href="./departamento/seleccionar/<?= $department->id ?>"
            class="col-md-4 text-decoration-none <?= $department->isActive() ?: 'opacity-50' ?>">
            <div class="btn btn-outline-primary card">
              <img
                src="<?= $department->hasIcon()
                        ? urldecode($department->iconFilePath->asString())
                        : './assets/img/department.png'
                      ?>"
                alt="Icono del departamento de <?= $department ?>"
                class="card-img-top object-fit-scale"
                height="164" />
              <div class="card-body">
                <strong class="h2 card-title"><?= $department ?></strong>
              </div>
            </div>
          </a>
        <?php endforeach ?>
      </div>
    </div>
  </div>
</main>
