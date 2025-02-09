<?php

$class ??= '';
$slot ??= '';

?>

<a
  @click="
    $event.preventDefault();
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
  <?= $slot ?>
</a>
