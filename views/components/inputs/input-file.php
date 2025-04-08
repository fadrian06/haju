<?php

declare(strict_types=1);

$required ??= true;
$id = uniqid();
$label ??= '';

?>

<label for="<?= $id ?>" class="form-label">
  <?php if ($required) : ?>
    <sub class="text-danger fs-1">*</sub>
  <?php endif ?>
  <?= $label ?>
</label>
<input
  type="file"
  class="form-control"
  id="<?= $id ?>"
  <?= !$required ?: 'required' ?> />
