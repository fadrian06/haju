<?php

declare(strict_types=1);

use HAJU\Models\User;

/**
 * @var ?User $user
 */

?>

<?php if ($user instanceof User) : ?>
  <?php Flight::render('components/logout-link', [
    'class' => 'd-none d-md-inline-flex btn btn-outline-primary align-items-center gap-1'
  ]) ?>
<?php else : ?>
  <a
    class="d-none d-md-inline-flex btn btn-outline-primary align-items-center gap-1 <?= !isActive('/ingresar') ?: 'disabled' ?>"
    href="./ingresar">
    <i class="fa fa-right-to-bracket"></i>
    Iniciar sesión
  </a>
  <a
    class="d-none d-md-inline-flex btn btn-outline-primary align-items-center gap-1 <?= !isActive('/registrate') ?: 'disabled' ?>"
    data-bs-toggle="modal"
    href="#registrate">
    <i class="fa fa-user-plus"></i>
    Regístrate
  </a>
<?php endif ?>
