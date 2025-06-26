<?php

use HAJU\Models\User;

/**
 * @var User $user
 */

?>

<main class="container rounded-3 my-4 py-4 d-flex flex-column justify-content-center text-center">
  <div class="row justify-content-center">
    <div class="col-12">
      <h1 class="mb-4">Seleccione un departamento</h1>
      <div class="row g-5">
        <?php foreach ($user->getDepartment() as $department) : ?>
          <a
            href="./departamento/seleccionar/<?= $department->id ?>"
            class="col-md-4 text-decoration-none <?= $department->isActive() ?: 'opacity-50' ?>">
            <div class="btn btn-outline-primary card h-100">
              <img
                src="<?= getDepartmentIconUrl($department) ?>"
                alt="Icono del departamento de <?= $department ?>"
                class="card-img-top object-fit-scale"
                height="164" />
              <div class="card-body d-flex flex-column align-items-center justify-content-center">
                <strong class="h2 card-title"><?= $department ?></strong>
              </div>
            </div>
          </a>
        <?php endforeach ?>
      </div>
    </div>
  </div>
</main>
