<?php

declare(strict_types=1);

use App\OldModels\Department;

/**
 * @var Department[] $departments
 */
assert(isset($departments) && is_array($departments), new Error('Departments not set'));

?>

<section class="mb-4 d-sm-flex px-0 align-items-center justify-content-between">
  <h2>Departamentos</h2>
</section>

<ul class="list-unstyled row row-cols-sm-2 row-cols-md-3">
  <?php foreach ($departments as $department) : ?>
    <li class="mb-4 d-flex align-items-stretch">
      <article class="card card-body text-center">
        <div class="dropdown position-relative">
          <button
            class="bg-transparent border-0 position-absolute end-0"
            data-bs-toggle="dropdown">
            <i class="ti-more"></i>
          </button>
          <div class="dropdown-menu">
            <a
              class="dropdown-item"
              href="./departamentos/<?= $department->id ?>/<?= $department->isActive() ? 'desactivar' : 'activar' ?>">
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
        <span class="custom-badge status-<?= $department->isActive() ? 'green' : 'red' ?> mx-4 mb-2">
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
