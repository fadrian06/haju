<?php

/** @var App\Models\User $user */
?>

<div class="col-lg-6">
  <article class="modal-content cs_modal">
    <header class="modal-header py-3">
      <h5 class="modal-title">(2/2) Cambiar contraseña</h5>
    </header>
    <form class="modal-body" method="post">
      <input type="number" min="0" hidden name="id" value="<?= $user->getId() ?>" />
      <label class="input-group mb-3">
        <i class="input-group-text ti-key fs-1"></i>
        <input type="password" name="password" class="form-control mb-0 w-auto" placeholder="Nueva contraseña" />
      </label>
      <button class="btn_1">Cambiar</button>
    </form>
  </article>
</div>
