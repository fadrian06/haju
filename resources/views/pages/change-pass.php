<?php

use HAJU\Models\User;

/** @var User $user */

?>

<section class="px-0 modal modal-content cs_modal w-auto">
  <header class="modal-header py-3">
    <h5>(2/2) Cambiar contraseña</h5>
  </header>
  <form class="modal-body" method="post">
    <input type="number" min="0" hidden name="id" value="<?= $user->id ?>" />
    <label class="input-group mb-3">
      <i class="input-group-text ti-key fs-1"></i>
      <input type="password" name="password" class="form-control mb-0 w-auto h-100 py-0" placeholder="Nueva contraseña" />
    </label>
    <button class="btn_1">Cambiar</button>
  </form>
</section>
