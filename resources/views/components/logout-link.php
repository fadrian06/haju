<?php

use HAJU\Enums\IconPosition;

$class ??= '';
$iconPosition ??= IconPosition::START;

?>

<a
  @click.prevent="
    customSwal.fire({
      title: '¿Estás seguro que deseas cerrar sesión?',
      icon: 'question',
      confirmButtonText: 'Sí, quiero salir',
      showDenyButton: true,
      denyButtonText: 'No, cancelar',
      preConfirm() {
        location.href = $el.href;
      },
    })
  "
  href="./salir"
  class="<?= $class ?>">
  <?php if ($iconPosition === IconPosition::START) : ?>
    <i class="fa fa-right-from-bracket"></i>
    Cerrar sesión
  <?php else : ?>
    Cerrar sesión
    <i class="fa fa-right-from-bracket"></i>
  <?php endif ?>
</a>
