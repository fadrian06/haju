<?php

use App\Models\Gender;
use App\Models\ProfessionPrefix;
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

<div class="modal fade" id="registrar">
  <div class="modal-dialog">
    <form class="modal-content" method="post">
      <header class="modal-header">
        <h3 class="modal-title fs-5">Registrar coordinador/a</h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </header>
      <section class="modal-body">
        <fieldset class="row">
          <summary class="fs-6 mb-2">Datos personales</summary>
          <div class="col-md-6 form-floating mb-4">
            <input class="form-control" name="first_name" required id="first_name" placeholder="Nombre" />
            <label for="first_name">Nombre</label>
          </div>
          <div class="col-md-6 form-floating mb-4">
            <input class="form-control" name="last_name" required id="last_name" placeholder="Apellido" />
            <label for="last_name">Apellido</label>
          </div>
          <div class="col-md-6 form-floating mb-4">
            <input type="date" class="form-control" name="birth_date" required id="birth_date" placeholder="Fecha de nacimiento" />
            <label for="birth_date">Fecha de nacimiento</label>
          </div>
          <div class="col-md-6 form-floating mb-4">
            <input type="number" min="0" class="form-control" name="id_card" required id="id_card" placeholder="Cédula" />
            <label for="id_card">Cédula</label>
          </div>
        </fieldset>
        <fieldset class="row">
          <div class="col-md-6 form-floating mb-4">
            <select class="form-select" name="gender" required id="gender" placeholder="Género">
              <option selected disabled>Seleccione un género</option>
              <?php foreach (Gender::cases() as $gender): ?>
                <option><?= $gender->value ?></option>
              <?php endforeach ?>
            </select>
            <label for="gender">Género</label>
          </div>
          <div class="col-md-6 form-floating mb-4">
            <select class="form-select" name="Prefijo" id="Prefijo" placeholder="Prefijo">
              <option value="">Seleccione un prefijo</option>
              <?php foreach (ProfessionPrefix::cases() as $prefix): ?>
                <option value="<?= $prefix->value ?>"><?= $prefix->getLongValue() ?></option>
              <?php endforeach ?>
              <option value="">Ninguno</option>
            </select>
            <label for="Prefijo">Prefijo</label>
          </div>
        </fieldset>
        <fieldset class="row">
          <summary class="fs-6 mb-2">Credenciales</summary>
          <div class="col-md-6 form-floating mb-4">
            <input type="password" class="form-control" name="password" required id="password" placeholder="Contraseña" />
            <label for="password">Contraseña</label>
          </div>
          <div class="col-md-6 form-floating mb-4">
            <input type="password" class="form-control" name="confirm_password" required id="confirm_password" placeholder="Confirmar contraseña" />
            <label for="confirm_password">Confirmar contraseña</label>
          </div>
        </fieldset>
        <fieldset class="row">
          <summary class="fs-6 mb-2">Datos de contacto</summary>
          <div class="col-md-6 form-floating mb-4">
            <input type="tel" class="form-control" name="phone" id="phone" placeholder="Teléfono" />
            <label for="phone">Teléfono</label>
          </div>
          <div class="col-md-6 form-floating mb-4">
            <input type="email" class="form-control" name="email" id="email" placeholder="Correo electrónico" />
            <label for="email">Correo electrónico</label>
          </div>
          <div class="col-md-6 form-floating mb-4">
            <textarea class="form-control" name="address" id="address" placeholder="Dirección"></textarea>
            <label for="address">Dirección</label>
          </div>
          <div class="col-md-6 form-floating mb-4">
            <input type="url" class="form-control" name="avatar" id="avatar" placeholder="Avatar URL" />
            <label for="avatar">Avatar URL</label>
          </div>
        </fieldset>
        <div class="form-check form-switch fs-6">
          <input class="form-check-input" name="is_active" type="checkbox" id="is_active" checked />
          <label class="form-check-label" for="is_active">
            Estado <small>(activo/inactivo)</small>
          </label>
        </div>
      </section>
      <footer class="modal-footer">
        <button class="btn btn-primary">Registrar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Cancelar
        </button>
      </footer>
    </form>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    if (location.href.endsWith('#registrar')) {
      new bootstrap.Modal('#registrar').show()
    }
  })
</script>