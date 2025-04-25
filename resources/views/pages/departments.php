<?php

declare(strict_types=1);

use HAJU\Models\Department;
use HAJU\Models\User;

/**
 * @var array<int, Department> $departments
 * @var User $user
 * @var ?string $error
 * @var ?string $message
 */

?>

<section class="container mb-4 d-sm-flex align-items-center justify-content-between">
  <h2>Departamentos</h2>
</section>

<div class="container">
  <ul class="list-unstyled row row-cols-sm-2 row-cols-md-3">
    <?php foreach ($departments as $department) : ?>
      <li class="mb-4 d-flex align-items-stretch">
        <article class="card card-body text-center">
          <div class="dropdown position-relative">
            <button style="right: 0" class="bg-transparent border-0 position-absolute" data-bs-toggle="dropdown">
              <i class="ti-more"></i>
            </button>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="./departamentos/<?= $department->id ?>/<?= $department->isActive() ? 'desactivar' : 'activar' ?>">
                <i class="ti-<?= $department->isActive() ? 'un' : '' ?>lock"></i>
                <?= $department->isActive() ? 'Desactivar' : 'Activar' ?>
              </a>
            </div>
          </div>
          <picture class="p-3">
            <img
              class="img-fluid"
              src="<?= urldecode($department->iconFilePath->asString()) ?>"
              style="height: 130px; object-fit: contain;"
              title="<?= $department->name ?>" />
          </picture>
          <span class="badge text-bg-<?= $department->isActive() ? 'success' : 'danger' ?> mx-4 mb-2">
            <?= $department->isActive() ? 'Activo' : 'Inactivo' ?>
          </span>
          <h4><?= $department->name ?></h4>
          <small class="text-muted">
            Fecha de registro: <?= $department->registeredDate ?>
          </small>
        </article>
      </li>
    <?php endforeach ?>
  </ul>
</div>
