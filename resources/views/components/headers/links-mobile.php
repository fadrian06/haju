<?php

declare(strict_types=1);

use HAJU\Models\User;

/** @var ?User $user */

?>

<li class="dropdown d-md-none">
  <button
    class="btn btn-link text-decoration-none py-2 px-0 px-lg-2 d-flex align-items-center"
    data-bs-toggle="dropdown"
    data-bs-display="static">
    <i class="fa fa-ellipsis"></i>
  </button>
  <ul class="dropdown-menu dropdown-menu-end py-0">
    <?php if ($user instanceof User) : ?>
      <li>
        <?php Flight::render('components/logout-link', [
          'class' => 'dropdown-item d-flex align-items-center p-3',
          'slot' => 'Cerrar sesión'
        ]) ?>
      </li>
    <?php else: ?>
      <li>
        <a
          href="./ingresar"
          class="dropdown-item d-flex align-items-center p-3">
          Iniciar sesión
        </a>
      </li>
      <li>
        <a
          data-bs-toggle="modal"
          href="#registrate"
          class="dropdown-item d-flex align-items-center p-3">
          Regístrate
        </a>
      </li>
    <?php endif ?>
  </ul>
</li>
