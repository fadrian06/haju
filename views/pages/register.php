<?php

use App\Models\Gender;
use App\Models\InstructionLevel;

/** @var ?string $error */

?>

<section class="px-0 modal modal-content cs_modal w-auto">
  <header class="modal-header py-3">
    <h5>Regístrate</h5>
  </header>
  <form enctype="multipart/form-data" class="modal-body" method="post">
    <?php if ($error) : ?>
      <div class="alert alert-danger alert-dismissible fade show">
        <?= $error ?>
        <button class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif ?>
    <label class="input-group mb-3">
      <i class="input-group-text ti-user fs-1"></i>
      <input required name="first_name" class="form-control mb-0 w-auto h-100 py-0" placeholder="Primer nombre" />
    </label>
    <label class="input-group mb-3">
      <i class="input-group-text ti-user fs-1"></i>
      <input required name="second_name" class="form-control mb-0 w-auto h-100 py-0" placeholder="Segundo nombre" />
    </label>
    <label class="input-group mb-3">
      <i class="input-group-text ti-user fs-1"></i>
      <input required name="first_last_name" class="form-control mb-0 w-auto h-100 py-0" placeholder="Primer apellido" />
    </label>
    <label class="input-group mb-3">
      <i class="input-group-text ti-user fs-1"></i>
      <input required name="second_last_name" class="form-control mb-0 w-auto h-100 py-0" placeholder="Segundo apellido" />
    </label>
    <label class="input-group mb-3">
      <i class="input-group-text ti-calendar fs-1"></i>
      <input type="date" required name="birth_date" class="form-control mb-0 w-auto h-100 py-0" />
    </label>
    <label class="input-group mb-3">
      <i class="input-group-text ti-pencil-alt fs-1"></i>
      <select name="gender" class="form-select" required>
        <option selected disabled>Género</option>
        <?php foreach (Gender::cases() as $gender) : ?>
          <option><?= $gender->value ?></option>
        <?php endforeach ?>
      </select>
    </label>
    <label class="input-group mb-3">
      <i class="input-group-text ti-pencil-alt fs-1"></i>
      <select name="instruction_level" class="form-select" required>
        <option selected disabled>Nivel de instrucción</option>
        <?php foreach (InstructionLevel::cases() as $instruction) : ?>
          <option value="<?= $instruction->value ?>"><?= $instruction->getLongValue() ?></option>
        <?php endforeach ?>
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
      <i class="input-group-text ti-key fs-1"></i>
      <input required type="password" name="confirm_password" class="form-control mb-0 w-auto h-100 py-0" placeholder="Confirmar contraseña" />
    </label>
    <label class="input-group mb-3">
      <i class="input-group-text ti-tablet fs-1"></i>
      <input required type="tel" name="phone" class="form-control mb-0 w-auto h-100 py-0" placeholder="Teléfono" />
    </label>
    <label class="input-group mb-3">
      <i class="input-group-text ti-email fs-1"></i>
      <input required type="email" name="email" class="form-control mb-0 w-auto h-100 py-0" placeholder="Correo electrónico" />
    </label>
    <label class="input-group mb-3">
      <i class="input-group-text ti-pin2 fs-1"></i>
      <textarea required name="address" class="form-control mb-0 w-auto h-100 py-2" rows="1" placeholder="Dirección"></textarea>
    </label>
    <label class="input-group mb-3">
      <i class="input-group-text ti-camera fs-1"></i>
      <input required type="file" name="profile_image" class="form-control mb-0 w-auto h-100 py-0" placeholder="Foto de perfil" />
    </label>
    <button class="btn_1">Registrarse</button>
  </form>
</section>
