<?php

$id = uniqid();
$required ??= true;
$slot ??= '';
$name ??= '';

?>

<div class="form-floating">
  <input
    id="<?= $id ?>"
    class="form-control"
    placeholder=" "
    name="<?= $name ?>" />
  <label for="<?= $id ?>">
    <?php if ($required): ?>
      <sub class="text-danger fs-1">*</sub>
    <?php endif ?>
    <?= $slot ?>
  </label>
</div>
