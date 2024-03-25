<?php

/**
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
    <?php
      echo render('components/input-group', [
        'type' => 'number',
        'name' => 'id_card',
        'placeholder' => 'Cédula'
      ]);

      echo render('components/input-group', [
        'type' => 'password',
        'name' => 'password',
        'placeholder' => 'Contraseña'
      ]);
    ?>
    <button class="btn_1">Ingresar</button>
    <!-- <a href="./recuperar" class="pass_forget_btn">¿Olvidó su contraseña?</a> -->
  </form>
</section>
