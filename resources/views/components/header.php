<?php

declare(strict_types=1);

use HAJU\Models\Department;
use HAJU\Models\User;
use HAJU\Enums\Appointment;

/**
 * @var User $user
 * @var Department $department
 * @var bool $canChangeDepartment
 */

?>

<header class="header_iner d-flex align-items-center justify-content-between py-1 sticky-top top-0 end-0 w-100">
  <button
    data-bs-toggle="offcanvas"
    data-bs-target="#sidebar"
    class="border-0 btn d-lg-none fa fa-bars-staggered fa-2x"
    @mouseenter="$el.classList.add('fa-beat')"
    @mouseleave="$el.classList.remove('fa-beat')">
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
  <div class="ms-2 header_right d-flex justify-content-between align-items-center">
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
