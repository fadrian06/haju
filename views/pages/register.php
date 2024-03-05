<?php

use App\Models\Gender;
use App\Models\ProfessionPrefix as Prefix;
use App\Models\Role;

/** @var ?string $error */
?>

<section class="px-0 modal modal-content cs_modal w-auto">
  <header class="modal-header py-3">
    <h5>Regístrate</h5>
  </header>
  <form class="modal-body" method="post">
    <?php if ($error) : ?>
      <div class="alert alert-danger alert-dismissible fade show">
        <?= $error ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif ?>
    <label class="input-group mb-3">
      <i class="input-group-text ti-user fs-1"></i>
      <input required name="first_name" class="form-control mb-0 w-auto h-100 py-0" placeholder="Nombre" />
    </label>
    <label class="input-group mb-3">
      <i class="input-group-text ti-user fs-1"></i>
      <input required name="last_name" class="form-control mb-0 w-auto h-100 py-0" placeholder="Apellido" />
    </label>
    <label class="input-group mb-3">
      <i class="input-group-text ti-calendar fs-1"></i>
      <input type="date" required name="birth_date" class="form-control mb-0 w-auto h-100 py-0" placeholder="Fecha de nacimiento" />
    </label>
    <label class="input-group mb-3">
      <i class="input-group-text ti-pencil-alt fs-1"></i>
      <select name="gender" class="form-select">
        <option selected disabled>Seleccione un género</option>
        <?php foreach (Gender::cases() as $gender) : ?>
          <option><?= $gender->value ?></option>
        <?php endforeach ?>
      </select>
    </label>
    <label class="input-group mb-3">
      <i class="input-group-text ti-pencil-alt fs-1"></i>
      <select name="prefix" class="form-select">
        <option selected disabled>Seleccione un prefijo</option>
        <?php foreach (Prefix::cases() as $prefix) : ?>
          <option value="<?= $prefix->value ?>"><?= $prefix->getLongValue() ?></option>
        <?php endforeach ?>
        <option value="">Ninguno</option>
      </select>
    </label>
    <label class="input-group mb-3">
      <i class="input-group-text ti-id-badge fs-1"></i>
      <input required type="number" name="id_card" min="0" class="form-control mb-0 w-auto h-100 py-0" placeholder="Cédula" />
    </label>
    <label class="input-group mb-3">
      <i class="input-group-text ti-key fs-1"></i>
      <input required type="password" name="password" class="form-control mb-0 w-auto h-100 py-0" placeholder="Contraseña" />
    </label>
    <label class="input-group mb-3">
      <i class="input-group-text ti-tablet fs-1"></i>
      <input type="tel" name="phone" class="form-control mb-0 w-auto h-100 py-0" placeholder="Teléfono" />
    </label>
    <label class="input-group mb-3">
      <i class="input-group-text ti-email fs-1"></i>
      <input type="email" name="email" class="form-control mb-0 w-auto h-100 py-0" placeholder="Correo electrónico" />
    </label>
    <label class="input-group mb-3">
      <i class="input-group-text ti-pin2 fs-1"></i>
      <textarea name="address" class="form-control mb-0 w-auto h-100 py-2" rows="1" placeholder="Dirección"></textarea>
    </label>
    <label class="input-group mb-3">
      <i class="input-group-text ti-camera fs-1"></i>
      <input type="url" name="avatar" class="form-control mb-0 w-auto h-100 py-0" placeholder="Avatar URL" />
    </label>
    <button class="btn_1">Registrarse</button>
    <p>
      ¿Ya tienes una cuenta?
      <a href="<?= route('/ingresar') ?>">Inicia sesión</a>
    </p>
  </form>
</section>
