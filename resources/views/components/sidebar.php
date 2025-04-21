<?php

declare(strict_types=1);

use HAJU\Models\Department;
use HAJU\Models\User;
use HAJU\Repositories\Domain\PatientRepository;
use HAJU\Enums\Appointment;
use flight\Container;

/**
 * @var User $user
 * @var Department $department
 */

$patients = Container::getInstance()->get(PatientRepository::class)->getAll();

$backgrounds = [
  'Estadística' => 'white',
  'Emergencia' => '#fff0df',
  'Hospitalización' => '#f0ffff',
];

$patientsListId = uniqid();

?>

<datalist id="<?= $patientsListId ?>">
  <?php foreach ($patients ?? [] as $patient) : ?>
    <option value="<?= $patient->idCard ?>"></option>
  <?php endforeach ?>
</datalist>

<aside
  class="sidebar overflow-y-scroll"
  style="background: <?= $backgrounds[$department->name] ?? 'white' ?>">
  <header
    class="logo m-0 d-flex align-items-center justify-content-between"
    style="background: <?= $backgrounds[$department->name] ?? 'white' ?>">
    <picture class="p-2">
      <img
        class="img-fluid"
        src="./assets/img/logo@light.png"
        data-bs-toggle="tooltip"
        title='Hospital "José Antonio Uzcátegui"' />
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
                <input
                  class="ps-5 py-2 small"
                  name="cedula"
                  style="height: unset"
                  required
                  type="number"
                  placeholder="Cédula..."
                  list="<?= $patientsListId ?>" />
              </div>
              <button class="ps-4">
                <img src="./assets/img/icons/icon_search.svg" />
              </button>
            </form>
          </div>
        </li>
        <?php if (!$department->isStatistics()) : ?>
          <li>
            <a
              href="./pacientes#registrar"
              <?= isActive('/pacientes') ? 'data-bs-toggle="modal"' : '' ?>
              data-bs-target="#registrar">
              <i class="ti-plus"></i>
              Registrar paciente
            </a>
          </li>
          <li>
            <a href="./consultas/registrar">
              <i class="ti-plus"></i>
              Registrar consulta
            </a>
          </li>
          <li>
            <a href="./hospitalizaciones/registrar">
              <i class="ti-plus"></i>
              Registrar hospitalización
            </a>
          </li>
        <?php endif ?>
      </ul>
    </li>

    <li class="<?= isActive('/hospitalizaciones') ? 'mm-active' : '' ?>">
      <a href="./hospitalizaciones">
        <img height="23" src="./assets/img/icons/hospitalizations.svg" />
        <span>Hospitalizaciones</span>
      </a>
    </li>

    <li class="<?= isActive('/consultas') ? 'mm-active' : '' ?>">
      <a href="./consultas">
        <img height="23" src="./assets/img/icons/stethoscope.svg" />
        <span>Consultas</span>
      </a>
    </li>

    <?php if ($user->appointment->isHigherThan(Appointment::Coordinator)) : ?>
      <li class="<?= isActive('/doctores') ? 'mm-active' : '' ?>">
        <a href="#" class="has-arrow">
          <img src="./assets/img/icons/user-md.svg" />
          <span>Doctores</span>
        </a>
        <ul class="pe-2">
          <li class="<?= isActive('/doctores') ? 'mm-active' : '' ?>">
            <a href="./doctores">
              <i class="ti-list"></i>
              Listado
            </a>
          </li>
          <li>
            <a
              href="./doctores#registrar"
              <?= isActive('/doctores') ? 'data-bs-toggle="modal"' : '' ?>
              data-bs-target="#registrar">
              <i class="ti-plus"></i>
              Registrar
            </a>
          </li>
        </ul>
      </li>
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
            <a
              href="./usuarios#registrar"
              <?= isActive('/usuarios') ? 'data-bs-toggle="modal"' : '' ?>
              data-bs-target="#registrar">
              <i class="ti-plus"></i>
              Registrar
            </a>
          </li>
        </ul>
      </li>
      <?php if ($user->appointment === Appointment::Director) : ?>
        <li class="<?= isActive('/departamentos') ? 'mm-active' : '' ?>">
          <a href="./departamentos">
            <img src="./assets/img/icons/hospital.svg" />
            <span>Departamentos</span>
          </a>
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
              <li class="<?= isActive('/logs') ? 'mm-active' : '' ?>">
                <a href="./logs">
                  <i class="ti-eye"></i>
                  Logs de usuarios
                </a>
              </li>
            <?php endif ?>
            <li class="<?= isActive('/configuracion/permisos') ? 'mm-active' : '' ?>">
              <a href="./configuracion/permisos">
                <i class="ti-key"></i>
                Asignar departamentos
              </a>
            </li>
            <?php if ($user->appointment->isDirector() || $user->hasDepartment('Estadística')) : ?>
              <li class="<?= isActive('/configuracion/respaldo-restauracion') ? 'mm-active' : '' ?>">
                <a href="./configuracion/respaldo-restauracion">
                  <i class="ti-import"></i>
                  Respaldo y restauración
                </a>
              </li>
            <?php endif ?>
            <?php if ($user->appointment->isHigherThan(Appointment::Coordinator)) : ?>
              <li class="<?= isActive('/configuracion/causas-de-consulta') ? 'mm-active' : '' ?>">
                <a href="./configuracion/causas-de-consulta" class="text-wrap">
                  <i class="ti-bookmark-alt"></i>
                  Configurar causas de consultas
                </a>
              </li>
            <?php endif ?>
          </ul>
        </li>
      <?php endif ?>
    <?php endif ?>
  </menu>
</aside>
