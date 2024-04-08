<section class="px-0 modal modal-content cs_modal w-auto">
  <header class="modal-header py-3">
    <h5>Bienvenido</h5>
  </header>
  <form class="modal-body" method="post">
    <?php
      render('components/input-group', [
        'type' => 'number',
        'name' => 'id_card',
        'placeholder' => 'Cédula',
        'cols' => 12
      ]);

      render('components/input-group', [
        'type' => 'password',
        'name' => 'password',
        'placeholder' => 'Contraseña',
        'cols' => 12
      ]);
    ?>
    <button class="btn_1">Ingresar</button>
    <!-- <a href="./recuperar" class="pass_forget_btn">¿Olvidó su contraseña?</a> -->
  </form>
</section>
