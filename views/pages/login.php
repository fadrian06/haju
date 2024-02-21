<?php

/**
 * @var bool $showRegister
 * @var ?string $error
 * @var ?string $message
 */
?>

<div class="col-lg-6">
  <article class="modal-content cs_modal">
    <header class="modal-header py-3">
      <h5 class="modal-title">Bienvenido</h5>
    </header>
    <form class="modal-body text-center" method="post">
      <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show">
          <?= $error ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php elseif ($message): ?>
        <div class="alert alert-success alert-dismissible fade show">
          <?= $message ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif ?>
      <label class="input-group mb-3">
        <i class="input-group-text ti-id-badge fs-1"></i>
        <input type="number" name="id_card" class="form-control mb-0 w-auto" placeholder="Cédula" />
      </label>
      <label class="input-group mb-3">
        <i class="input-group-text ti-key fs-1"></i>
        <input type="password" name="password" class="form-control mb-0 w-auto" placeholder="Contraseña" />
      </label>
      <button class="btn_1 mt-0">Ingresar</button>
      <?php if ($showRegister) : ?>
        <p>¿Necesitas una cuenta?
          <a href="<?= route('/registrate') ?>">Regístrate</a>
        </p>
      <?php else: ?>
        <a href="<?= route('/recuperar') ?>" class="pass_forget_btn">¿Olvidó su contraseña?</a>
      <?php endif ?>
    </form>
  </article>
</div>
