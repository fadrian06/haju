<?php

use App\Models\User;
use App\Models\Role;

/** @var User $user */

?>

<aside class="sidebar">
  <header class="logo m-0 d-flex align-items-center justify-content-between">
    <picture class="p-2">
      <img class="img-fluid" src="<?= asset('img/logo.png') ?>" />
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
      <a href="<?= route('/') ?>">
        <img src="<?= asset('img/icons/house.svg') ?>" />
        <span>Inicio</span>
      </a>
    </li>
    <?php if ($user->role->isHigherThan(Role::Coordinator)) : ?>
      <li class="<?= isActive('/usuarios') ? 'mm-active' : '' ?>">
        <a href="#" class="has-arrow">
          <img src="<?= asset('img/icons/users.svg') ?>" />
          <span>Usuarios</span>
        </a>
        <ul>
          <li>
            <a href="<?= route('/usuarios') ?>">
              <i class="ti-list"></i>
              Listado
            </a>
          </li>
          <li>
            <a href="<?= route('/usuarios') ?>#registrar" <?= isActive('/usuarios') ? 'data-bs-toggle="modal"' : '' ?> data-bs-target="#registrar">
              <i class="ti-plus"></i>
              Registrar
            </a>
          </li>
        </ul>
      </li>
      <?php if ($user->role === Role::Director) : ?>
        <li class="<?= isActive('/departamentos') ? 'mm-active' : '' ?>">
          <a href="#" class="has-arrow">
            <img src="<?= asset('img/icons/hospital.svg') ?>" />
            <span>Departamentos</span>
          </a>
          <ul>
            <li>
              <a href="<?= route('/departamentos') ?>">
                <i class="ti-list"></i>
                Listado
              </a>
            </li>
            <li>
              <a href="<?= route('/departamentos') ?>#registrar" <?= isActive('/departamentos') ? 'data-bs-toggle="modal"' : '' ?> data-bs-target="#registrar">
                <i class="ti-plus"></i>
                Registrar
              </a>
            </li>
          </ul>
        </li>
        <li class="<?= isActive('/configuracion') ? 'mm-active' : '' ?>">
          <a href="#" class="has-arrow">
            <img src="<?= asset('img/icons/gears.svg') ?>" />
            <span>Configuraciones</span>
          </a>
          <ul>
            <!-- <li>
              <a href="<?= route('/configuracion') ?>">
                <i class="ti-bookmark-alt"></i>
                Institución
              </a>
            </li> -->
            <li>
              <a href="<?= route('/configuracion') ?>#roles-y-permisos">
                <i class="ti-key"></i>
                Roles y permisos
              </a>
            </li>
            <!-- <li>
              <a href="<?= route('/configuracion') ?>#respaldo-y-restauracion">
                <i class="ti-import"></i>
                Respaldo y restauración
              </a>
            </li> -->
          </ul>
        </li>
      <?php endif ?>
    <?php endif ?>
  </menu>
</aside>

<script>
  document.addEventListener('DOMContentLoaded', () => $('#sidebar_menu').metisMenu())
</script>
