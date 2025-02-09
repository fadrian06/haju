<?php

use Leaf\Http\Session;

$userId = Session::get('userId');

?>

<?php if ($userId): ?>
  <?php renderComponent('logout-link', ['class' => 'd-none d-md-block', 'slot' => 'Cerrar sesión']) ?>
<?php else: ?>
  <a class="d-none d-md-block" href="./ingresar">Iniciar sesión</a>
  <a class="d-none d-md-block" data-bs-toggle="modal" href="#registrate">
    Regístrate
  </a>
<?php endif ?>
