<?php

/**
 * @var bool $showRegister
 * @var ?string $error
 * @var ?string $message
 */
?>

<section class="px-0 modal modal-content cs_modal w-auto">
  <header class="modal-header py-3">
    <h5>Bienvenido</h5>
  </header>
  <form class="modal-body text-center" method="post">
    <?php $error && render('components/notification', ['type' => 'error', 'text' => $error]) ?>
    <?php $message && render('components/notification', ['type' => 'message', 'text' => $message]) ?>
    <label class="input-group mb-3">
      <i class="input-group-text ti-id-badge fs-1"></i>
      <input type="number" required name="id_card" class="form-control mb-0 w-auto h-100 py-0" placeholder="Cédula" />
    </label>
    <label class="input-group mb-3">
      <i class="input-group-text ti-key fs-1"></i>
      <input type="password" required name="password" class="form-control mb-0 w-auto h-100 py-0" placeholder="Contraseña" />
    </label>
    <button class="btn_1">Ingresar</button>
    <?php if ($showRegister) : ?>
      <p>¿Necesitas una cuenta?
        <a href="<?= route('/registrate') ?>">Regístrate</a>
      </p>
    <?php // else : ?>
      <!-- <a href="<?= route('/recuperar') ?>" class="pass_forget_btn">¿Olvidó su contraseña?</a> -->
    <?php endif ?>
  </form>
</section>
