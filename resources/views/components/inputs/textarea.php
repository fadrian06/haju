<?php

declare(strict_types=1);

$id = uniqid();
$required ??= true;
$label ??= '';
$value ??= '';
$name ??= '';

?>

<div class="form-floating">
  <textarea
    id="<?= $id ?>"
    class="form-control"
    placeholder=" "
    <?= !$required ?: 'required' ?>
    name="<?= $name ?>"><?= $value ?></textarea>
  <label for="<?= $id ?>">
    <?php if ($required) : ?>
      <sub class="text-danger fs-1">*</sub>
    <?php endif ?>
    <?= $label ?>
  </label>
</div>
