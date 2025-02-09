<?php

use App\Models\User;
use Leaf\Http\Session;

/** @var ?User $user */

$userId = Session::get('userId');

?>

<li class="dropdown d-lg-none">
  <button
    class="btn btn-link py-2 px-0 px-lg-2 d-flex align-items-center"
    data-bs-toggle="dropdown"
    data-bs-display="static">
    <?php renderComponent('icons/three-dots') ?>
  </button>
  <ul class="dropdown-menu dropdown-menu-end py-0">
    <?php if ($userId): ?>
      <li>
        <?php renderComponent('logout-link', [
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
