<section class="px-0 modal modal-content cs_modal w-auto" style="z-index: 0">
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
    <center>
      <a href="#registrate" data-bs-toggle="modal" class="pass_forget_btn">
        ¿No tienes cuenta?, regístrate
      </a>
    </center>
  </form>
</section>

<div class="modal fade" id="registrate">
  <div class="modal-dialog modal-dialog-scrollable">
    <form action="./registrate" class="modal-content" method="post">
      <header class="modal-header">
        <h3 class="modal-title fs-5">Introduce la clave maestra</h3>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </header>
      <section class="modal-body">
        <?php

        render('components/input-group', [
          'name' => 'secret_key',
          'placeholder' => 'Clave maestra',
        ]);

        ?>
      </section>
      <footer class="modal-footer">
        <button class="btn btn-primary">Continuar</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
          Cancelar
        </button>
      </footer>
    </form>
  </div>
</div>
