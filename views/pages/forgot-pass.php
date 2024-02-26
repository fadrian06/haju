<?php

/** @var ?string $error */
?>

<section class="px-0 modal-content cs_modal w-auto">
  <header class="modal-header py-3">
    <h5>(1/2) Recuperar contraseña</h5>
  </header>
  <form class="modal-body" method="post">
    <?php if ($error) : ?>
      <div class="alert alert-danger alert-dismissible fade show">
        <?= $error ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif ?>
    <label class="input-group mb-3">
      <i class="input-group-text ti-id-badge fs-1"></i>
      <input type="number" name="id_card" class="form-control mb-0 w-auto h-100 py-0" placeholder="Cédula" />
    </label>
    <button class="btn_1">Recuperar</button>
  </form>
</section>
