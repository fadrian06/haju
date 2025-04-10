<?php

declare(strict_types=1);

?>

<div class="modal fade" id="registrate">
  <div class="modal-dialog modal-dialog-scrollable">
    <form
      @submit.prevent="
        const options = {
          method: 'post',
          body: new FormData($el),
        };

        fetch('./api/verificar-clave-maestra', options)
          .then(async response => {
            if (!response.ok) {
              return customSwal.fire({
                title: await response.json(),
                icon: 'error'
              });
            }

            location.href = './registrate';
          });
      "
      class="modal-content"
      method="post">
      <header class="modal-header">
        <h3 class="modal-title fs-5">Introduce la clave maestra</h3>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal">
        </button>
      </header>
      <section class="modal-body">
        <?php Flight::render('components/inputs/input-password', [
          'name' => 'secret_key',
          'label' => 'Clave maestra',
        ]) ?>
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
