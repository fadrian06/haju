<?php

use App\Models\User;

/**
 * @var array<int, User> $users
 * @var ?string $error
 * @var ?string $message
 */

?>

<section class="mb-4 d-md-flex px-0 align-items-center justify-content-between">
  <h2>Usuarios</h2>
  <a data-bs-toggle="modal" href="#registrar" class="btn btn-primary rounded-pill d-flex align-items-center">
    <i class="px-2 ti-plus"></i>
    <span class="px-2">Añadir usuario</span>
  </a>
</section>
<ul class="list-unstyled row row-cols-sm-2 row-cols-md-3">
  <?php foreach ($users as $user) : ?>
    <li class="mb-4">
      <article class="card card-body text-center">
        <div class="dropdown position-relative">
          <button style="right: 0" class="bg-transparent border-0 position-absolute" data-bs-toggle="dropdown">
            <i class="ti-more"></i>
          </button>
          <div class="dropdown-menu">
            <a class="dropdown-item" href="<?= route('/usuarios/@id/' . ($user->isActive ? 'desactivar' : 'activar'), ['id' => $user->getId()]) ?>">
              <i class="ti-<?= $user->isActive ? 'un' : '' ?>lock"></i>
              <?= $user->isActive ? 'Desactivar' : 'Activar' ?>
            </a>
          </div>
        </div>
        <img class="img-fluid p-3 rounded-circle" src="<?= $user->avatar?->asString() ?? asset('img/user.jpg') ?>" />
        <span class="custom-badge status-<?= $user->isActive ? 'green' : 'red' ?> mx-4 mb-2">
          <?= $user->isActive ? 'Activo' : 'Inactivo' ?>
        </span>
        <h4><?= $user->getFullName() ?></h4>
        <span><?= $user->role->value ?></span>
        <small class="text-muted">
          <i class="ti-pin2"></i>
          <?= $user->address ?>
        </small>
      </article>
    </li>
  <?php endforeach ?>
</ul>
