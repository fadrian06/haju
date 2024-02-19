<?php

/** @var bool $showRegister */
?>

<div class="col-lg-6">
  <article class="modal-content cs_modal">
    <header class="modal-header py-3">
      <h5 class="modal-title">Bienvenido</h5>
    </header>
    <form class="modal-body text-center" method="post">
      <label class="input-group mb-3">
        <i class="input-group-text ti-id-badge fs-1"></i>
        <input type="number" name="idCard" class="form-control mb-0 w-auto" placeholder="Cédula" />
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
      <?php endif ?>
      <a href="<?= route('/recuperar') ?>" class="pass_forget_btn">¿Olvidó su contraseña?</a>
    </form>
  </article>
</div>
