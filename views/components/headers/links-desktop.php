<?php

declare(strict_types=1);

use App\Models\User;

/** @var ?User $user */
$user ??= null;

?>

<?php if ($user instanceof User) : ?>
  <?php Flight::render('components/logout-link', [
    'class' => 'd-none d-md-block',
    'slot' => 'Cerrar sesión'
  ]) ?>
<?php else : ?>
  <a
    class="d-flex align-items-center gap-1 text-decoration-none"
    href="./ingresar">
    <i class="fa fa-right-to-bracket"></i>
    Iniciar sesión
  </a>
  <a
    class="d-flex align-items-center gap-1 text-decoration-none"
    data-bs-toggle="modal"
    href="#registrate">
    <i class="fa fa-user-plus"></i>
    Regístrate
  </a>
<?php endif ?>
