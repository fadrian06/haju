<?php

declare(strict_types=1);

?>

<main class="container bg-white rounded-3 my-4 py-4 d-flex flex-column justify-content-center">
  <div class="row justify-content-center">
    <div class="col-11 col-md-5">
      <form method="post" class="card bg-transparent text-center">
        <div class="card-header border-bottom-0 py-3 px-4">
          <h1 class="card-title fw-bold h5 m-0">
            Introduce tus credenciales para continuar
          </h1>
        </div>
        <div class="card-body text-center px-4 pb-4 d-grid gap-4">
          <?php

          Flight::render('components/inputs/input', [
            'type' => 'number',
            'name' => 'id_card',
            'label' => 'Cédula',
          ]);

          Flight::render('components/inputs/input-password', [
            'name' => 'password',
            'label' => 'Contraseña',
          ]);

          ?>
          <button class="btn btn-primary">Ingresar</button>
          <a data-bs-toggle="modal" href="#registrate">
            ¿No tienes cuenta?, regístrate
          </a>
        </div>
      </form>
    </div>
  </div>
</main>
