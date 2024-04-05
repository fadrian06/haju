<?php

/**
 * @var bool $showRestore
 * @var ?string $error
 * @var ?string $message
 */

?>

<?php $error && render('components/notification', ['type' => 'error', 'text' => $error]) ?>
<?php $message && render('components/notification', ['type' => 'message', 'text' => $message]) ?>

<div class="col-sm-6 p-2">
  <a href="./configuracion/respaldar" class="btn w-100 btn-light shadow p-4">
    <i class="mb-3 ti-save fs-1"></i>
    <h2>Respaldar</h2>
  </a>
</div>
<?php if ($showRestore) : ?>
  <div class="col-sm-6 p-2">
    <a href="./configuracion/restaurar" class="btn w-100 btn-light shadow p-4">
      <i class="mb-3 ti-reload fs-1"></i>
      <h2>Restaurar</h2>
    </a>
  </div>
<?php endif ?>
