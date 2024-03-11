<?php

use App\Models\Department;
use App\Models\Role;
use App\Models\User;

/**
 * @var User $user
 * @var Department $department
 */

?>

<header class="header_iner d-flex align-items-center py-1">
  <button class="sidebar_icon d-lg-none me-2">
    <i class="ti-menu"></i>
  </button>
  <h2 class="m-0 d-flex align-items-center">
    <span class="d-none d-sm-block h3 m-0">Departamento de <?= $department->name ?></span>
    <span class="d-block d-sm-none h6 m-0">Departamento de <?= $department->name ?></span>
    <a href="<?= route('/departamento/seleccionar') ?>" class="ms-4 btn btn-outline-primary btn-sm">
      Cambiar
    </a>
  </h2>
  <div class="serach_field-area m-0">
    <!-- <form class="search_inner">
      <div class="search_field">
        <input required type="search" placeholder="Buscar...">
      </div>
      <button>
        <img src="<?= asset('img/icon/icon_search.svg') ?>" />
      </button>
    </form> -->
  </div>
  <div class="ms-2 header_right d-flex justify-content-between align-items-center">
    <!-- <ul class="header_notification_warp d-flex align-items-center">
      <li>
        <a href="<?= route('/notificaciones') ?>">
          <img src="<?= asset('img/icon/bell.svg') ?>" />
        </a>
      </li>
    </ul> -->
    <div class="profile_info">
      <img src="<?= $user->avatar?->asString() ?? asset('img/client_img.png') ?>" />
      <div class="profile_info_iner">
        <p><?= $user->getParsedRole() ?></p>
        <h5><?= "{$user->prefix?->value} {$user->getFullName()}" ?></h5>
        <div class="profile_info_details">
          <a href="<?= route('/perfil') ?>">
            Mi perfil
            <i class="ti-user"></i>
          </a>
          <?php if ($user->role === Role::Director) : ?>
            <a href="<?= route('/configuracion') ?>">
              Configuración
              <i class="ti-settings"></i>
            </a>
          <?php endif ?>
          <a href="<?= route('/salir') ?>">
            Cerrar sesión
            <i class="ti-shift-left"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</header>
