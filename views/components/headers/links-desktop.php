<?php

declare(strict_types=1);

use Leaf\Http\Session;

$userId = container()->get(Session::class)->get('userId');

?>

<?php if ($userId): ?>
  <?php Flight::render('components/logout-link', ['class' => 'd-none d-md-block', 'slot' => 'Cerrar sesión']) ?>
<?php else: ?>
  <a class="d-none d-md-block" href="./ingresar">Iniciar sesión</a>
  <a class="d-none d-md-block" data-bs-toggle="modal" href="#registrate">
    Regístrate
  </a>
<?php endif ?>
