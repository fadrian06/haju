<?php

use HAJU\Models\Department;
use HAJU\Models\User;
use HAJU\ValueObjects\Appointment;

/**
 * @var User $user
 * @var Department $department
 * @var bool $canChangeDepartment
 */

?>

<header class="header_iner d-flex align-items-center py-1 position-fixed top-0 end-0 w-100" style="height: 65px">
  <button class="sidebar_icon d-lg-none me-2">
    <i class="ti-menu"></i>
  </button>
  <h2 class="m-0 d-flex align-items-center">
    <span class="d-none d-sm-block h3 m-0 text-nowrap">Departamento de <?= $department->name ?></span>
    <span class="d-block d-sm-none h6 m-0 text-nowrap">Departamento de <?= $department->name ?></span>
    <?php if ($canChangeDepartment) : ?>
      <a href="./departamento/seleccionar" class="ms-4 btn btn-outline-primary btn-sm">
        Cambiar
      </a>
    <?php endif ?>
  </h2>
  <div class="serach_field-area m-0">
    <!-- <form class="search_inner">
      <div class="search_field">
        <input required type="search" placeholder="Buscar...">
      </div>
      <button>
        <img src="./assets/img/icon/icon_search.svg" />
      </button>
    </form> -->
  </div>
  <div class="ms-2 header_right d-flex justify-content-between align-items-center">
    <!-- <ul class="header_notification_warp d-flex align-items-center">
      <li>
        <a href="./notificaciones">
          <img src="./assets/img/icon/bell.svg" />
        </a>
      </li>
    </ul> -->
    <div class="profile_info">
      <img class="p-2" style="max-width: unset" src="<?= urldecode($user->profileImagePath->asString()) ?>" />
      <div class="profile_info_iner">
        <p><?= $user->getParsedAppointment() ?></p>
        <h5><?= "{$user->instructionLevel->value}. {$user->getFullName()}" ?></h5>
        <div class="profile_info_details">
          <a href="./perfil">
            Mi perfil
            <i class="ti-user"></i>
          </a>
          <?php if ($user->appointment === Appointment::Director) : ?>
            <a href="./configuracion/institucion">
              Configuración
              <i class="ti-settings"></i>
            </a>
          <?php endif ?>
          <a href="./salir">
            Cerrar sesión
            <i class="ti-shift-left"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</header>
