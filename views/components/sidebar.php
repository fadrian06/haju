<?php

use App\Models\Department;
use App\Models\User;
use App\ValueObjects\Appointment;

/**
 * @var User $user
 * @var Department $department
 */

?>

<aside class="sidebar" style="overflow-y: scroll">
  <header class="logo m-0 d-flex align-items-center justify-content-between">
    <picture class="p-2">
      <img class="img-fluid" src="./assets/img/logo.png" data-bs-toggle="tooltip" title='Hospital "José Antonio Uzcátegui"' />
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
    <?php if ($user->appointment->isHigherThan(Appointment::Secretary)) : ?>
      <li class="<?= isActive('/pacientes') ? 'mm-active' : '' ?>">
        <a href="#" class="has-arrow">
          <img src="./assets/img/icons/patient.svg" />
          <span>Pacientes</span>
        </a>
        <ul class="pe-2">
          <li class="<?= isActive('/pacientes') ? 'mm-active' : '' ?>">
            <a href="./pacientes">
              <i class="ti-list"></i>
              Listado
            </a>
          </li>
          <li class="<?= isActive('/pacientes') ? 'mm-active' : '' ?>">
            <div class="serach_field-area m-0 w-100">
              <form class="search_inner" action="./pacientes">
                <div class="search_field">
                  <input class="ps-5 py-2 small" name="cedula" style="height: unset" required type="number" placeholder="Cédula...">
                </div>
                <button class="ps-4">
                  <img src="./assets/img/icons/icon_search.svg" />
                </button>
              </form>
            </div>
          </li>
          <?php if ($user->appointment !== Appointment::Director) : ?>
            <li>
              <a href="./pacientes#registrar" <?= isActive('/pacientes') ? 'data-bs-toggle="modal"' : '' ?> data-bs-target="#registrar">
                <i class="ti-plus"></i>
                Registrar paciente
              </a>
            </li>
            <li>
              <a href="./consultas/registrar" <?= isActive('/consultas/registrar') ? 'data-bs-toggle="modal"' : '' ?> data-bs-target="#registrar">
                <i class="ti-plus"></i>
                Registrar consulta
              </a>
            </li>
          <?php endif ?>
        </ul>
      </li>
    <?php endif ?>
    <?php if ($user->appointment->isHigherThan(Appointment::Coordinator)) : ?>
      <li class="<?= isActive('/usuarios') ? 'mm-active' : '' ?>">
        <a href="#" class="has-arrow">
          <img src="./assets/img/icons/users.svg" />
          <span>Usuarios</span>
        </a>
        <ul class="pe-2">
          <li class="<?= isActive('/usuarios') ? 'mm-active' : '' ?>">
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
      <?php if ($user->appointment === Appointment::Director && $department->name === 'Estadística') : ?>
        <li class="<?= isActive('/departamentos') ? 'mm-active' : '' ?>">
          <a href="#" class="has-arrow">
            <img src="./assets/img/icons/hospital.svg" />
            <span>Departamentos</span>
          </a>
          <ul class="pe-2">
            <li class="<?= isActive('/departamentos') ? 'mm-active' : '' ?>">
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
          <ul class="pe-2">
            <?php if ($user->appointment === Appointment::Director) : ?>
              <li class="<?= isActive('/configuracion/institucion') ? 'mm-active' : '' ?>">
                <a href="./configuracion/institucion">
                  <i class="ti-bookmark-alt"></i>
                  Institución
                </a>
              </li>
            <?php endif ?>
            <li class="<?= isActive('/configuracion/permisos') ? 'mm-active' : '' ?>">
              <a href="./configuracion/permisos">
                <i class="ti-key"></i>
                Roles y permisos
              </a>
            </li>
            <?php if ($user->appointment === Appointment::Director || $user->hasDepartment('Estadística')) : ?>
              <li class="<?= isActive('/configuracion/respaldo-restauracion') ? 'mm-active' : '' ?>">
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
