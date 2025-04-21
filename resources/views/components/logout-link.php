<?php

declare(strict_types=1);

$class ??= '';

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
  <i class="fa fa-right-from-bracket"></i>
  Cerrar sesión
</a>
