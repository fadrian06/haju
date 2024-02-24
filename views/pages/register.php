<?php
  use App\Models\GenrePrefix as Prefix;
?>

<div class="col-lg-6">
  <article class="modal-content cs_modal">
    <header class="modal-header py-3">
      <h5 class="modal-title">Regístrate</h5>
    </header>
    <form class="modal-body" method="post">
      <label class="input-group mb-3">
        <i class="input-group-text ti-user fs-1"></i>
        <input required name="first_name" class="form-control mb-0 w-auto" placeholder="Nombre" />
      </label>
      <label class="input-group mb-3">
        <i class="input-group-text ti-user fs-1"></i>
        <input required name="last_name" class="form-control mb-0 w-auto" placeholder="Apellido" />
      </label>
      <label class="input-group mb-3">
        <i class="input-group-text ti-cup fs-1"></i>
        <input required name="speciality" class="form-control mb-0 w-auto" placeholder="Especialidad" />
      </label>
      <label class="input-group mb-3">
        <i class="input-group-text ti-pencil-alt fs-1"></i>
        <select name="prefix" class="form-select mb-0 w-auto">
          <option selected disabled value="">Prefijo</option>
          <?php foreach (Prefix::cases() as $prefix): ?>
            <option><?= $prefix->value ?></option>
          <?php endforeach ?>
          <option value="">Ninguno</option>
        </select>
      </label>
      <label class="input-group mb-3">
        <i class="input-group-text ti-id-badge fs-1"></i>
        <input required type="number" name="id_card" min="0" class="form-control mb-0 w-auto" placeholder="Cédula" />
      </label>
      <label class="input-group mb-3">
        <i class="input-group-text ti-key fs-1"></i>
        <input required type="password" name="password" class="form-control mb-0 w-auto" placeholder="Contraseña" />
      </label>
      <label class="input-group mb-3">
        <i class="input-group-text ti-camera fs-1"></i>
        <input type="url" name="avatar" class="form-control mb-0 w-auto" placeholder="Avatar URL" />
      </label>
      <button class="btn_1 mt-0">Registrarse</button>
      <p>
        ¿Ya tienes una cuenta?
        <a href="<?= route('/ingresar') ?>">Inicia sesión</a>
      </p>
    </form>
  </article>
</div>
