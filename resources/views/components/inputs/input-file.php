<?php



$required ??= true;
$id = uniqid();
$label ??= '';
$name ??= '';

?>

<label for="<?= $id ?>" class="form-label">
  <?php if ($required) : ?>
    <sub class="text-danger fs-1">*</sub>
  <?php endif ?>
  <?= $label ?>
</label>
<input
  name="<?= $name ?>"
  type="file"
  class="form-control"
  id="<?= $id ?>"
  <?= !$required ?: 'required' ?> />
