<?php

use HAJU\Models\User;

/**
 * @var ?User $user
 */

?>

<li class="dropdown d-md-none">
  <button
    class="btn btn-link text-decoration-none p-2 d-flex align-items-center"
    data-bs-toggle="dropdown"
    data-bs-display="static">
    <i class="fa fa-ellipsis"></i>
  </button>
  <ul class="dropdown-menu dropdown-menu-end py-0">
    <?php if ($user instanceof User) : ?>
      <li>
        <?php Flight::render('components/logout-link', [
          'class' => 'dropdown-item d-flex align-items-center gap-1 p-3',
        ]) ?>
      </li>
    <?php else : ?>
      <li>
        <a
          href="./ingresar"
          class="dropdown-item d-flex align-items-center gap-1 p-3">
          <i class="fa fa-right-to-bracket"></i>
          Iniciar sesión
        </a>
      </li>
      <li>
        <a
          data-bs-toggle="modal"
          href="#registrate"
          class="dropdown-item d-flex align-items-center gap-1 p-3">
          <i class="fa fa-user-plus"></i>
          Regístrate
        </a>
      </li>
    <?php endif ?>
  </ul>
</li>
