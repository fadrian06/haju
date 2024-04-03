<?php

use App\Models\User;
use App\ValueObjects\Appointment;

/** @var User $user */

?>

<aside class="sidebar" style="overflow-y: scroll">
  <header class="logo m-0 d-flex align-items-center justify-content-between">
    <picture class="p-2">
      <img class="img-fluid" src="./assets/img/logo.png" />
    </picture>
    <div class="sidebar_close_icon d-flex align-items-center d-lg-none">
      <i class="ti-close"></i>
    </div>
  </header>
  <menu class="m-0 p-0 pb-5" id="sidebar_menu">
    <li class="side_menu_title">
      <span>Panel de Administración</span>
    </li>
    <li class="<?= isActive('/') ? 'mm-active' : '' ?>">
      <a href="./">
        <img src="./assets/img/icons/house.svg" />
        <span>Inicio</span>
      </a>
    </li>
    <?php if ($user->appointment->isHigherThan(Appointment::Coordinator)) : ?>
      <li class="<?= isActive('/usuarios') ? 'mm-active' : '' ?>">
        <a href="#" class="has-arrow">
          <img src="./assets/img/icons/users.svg" />
          <span>Usuarios</span>
        </a>
        <ul>
          <li>
            <a href="./usuarios">
              <i class="ti-list"></i>
              Listado
            </a>
          </li>
          <li>
            <a href="./usuarios#registrar" <?= isActive('/usuarios') ? 'data-bs-toggle="modal"' : '' ?> data-bs-target="#registrar">
              <i class="ti-plus"></i>
              Registrar
            </a>
          </li>
        </ul>
      </li>
      <?php if ($user->appointment === Appointment::Director) : ?>
        <li class="<?= isActive('/departamentos') ? 'mm-active' : '' ?>">
          <a href="#" class="has-arrow">
            <img src="./assets/img/icons/hospital.svg" />
            <span>Departamentos</span>
          </a>
          <ul>
            <li>
              <a href="./departamentos">
                <i class="ti-list"></i>
                Listado
              </a>
            </li>
            <li>
              <a href="./departamentos#registrar" <?= isActive('/departamentos') ? 'data-bs-toggle="modal"' : '' ?> data-bs-target="#registrar">
                <i class="ti-plus"></i>
                Registrar
              </a>
            </li>
          </ul>
        </li>
      <?php endif ?>
      <?php if ($user->appointment->isHigherThan(Appointment::Coordinator)) : ?>
        <li class="<?= isActive('/configuracion/institucion', '/configuracion/permisos', '/configuracion/respaldo-restauracion') ? 'mm-active' : '' ?>">
          <a href="#" class="has-arrow">
            <img src="./assets/img/icons/gears.svg" />
            <span>Configuraciones</span>
          </a>
          <ul>
            <?php if ($user->appointment === Appointment::Director) : ?>
              <li>
                <a href="./configuracion/institucion">
                  <i class="ti-bookmark-alt"></i>
                  Institución
                </a>
              </li>
            <?php endif ?>
            <li>
              <a href="./configuracion/permisos">
                <i class="ti-key"></i>
                Roles y permisos
              </a>
            </li>
            <?php if ($user->appointment === Appointment::Director || $user->hasDepartment('Estadística')) : ?>
              <li>
                <a href="./configuracion/respaldo-restauracion">
                  <i class="ti-import"></i>
                  Respaldo y restauración
                </a>
              </li>
            <?php endif ?>
          </ul>
        </li>
      <?php endif ?>
    <?php endif ?>
  </menu>
</aside>

<script>
  document.addEventListener('DOMContentLoaded', () => $('#sidebar_menu').metisMenu())
</script>
