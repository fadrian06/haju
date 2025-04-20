<?php

/**
 * @var bool $showRestore
 * @var ?string $error
 * @var ?string $message
 * @var ?string $scriptPath
 */

?>

<div class="col-sm p-2">
  <a href="./configuracion/respaldar" class="btn w-100 btn-light shadow p-4">
    <i class="mb-3 ti-save fs-1"></i>
    <h2>Respaldar</h2>
  </a>
</div>
<?php if ($scriptPath) : ?>
  <div class="col-sm p-2">
    <a href="<?= $scriptPath ?>" download="haju.sqlite.sql" class="btn w-100 btn-light shadow p-4">
      <i class="mb-3 ti-download fs-1"></i>
      <h2>Descargar</h2>
    </a>
  </div>
<?php endif ?>
<?php if ($showRestore) : ?>
  <div class="col-sm p-2">
    <a href="./configuracion/restaurar" class="btn w-100 btn-light shadow p-4">
      <i class="mb-3 ti-reload fs-1"></i>
      <h2>Restaurar</h2>
    </a>
  </div>
<?php endif ?>
<form method="post" enctype="multipart/form-data" class="col-md p-2">
  <label class="btn w-100 btn-light shadow p-4">
    <input
      onchange="this.form.submit()"
      type="file"
      name="script"
      accept=".sql"
      hidden />
    <i class="mb-3 ti-upload fs-1"></i>
    <h2>Cargar respaldo y restaurar</h2>
  </label>
</form>
