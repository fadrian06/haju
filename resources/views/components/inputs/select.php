<?php

declare(strict_types=1);

use Leaf\Http\Session;

$id = uniqid();
$options ??= [];
$required ??= true;
$label ??= '';
$name ??= '';
$model ??= '';

?>

<div class="form-floating">
  <select
    data-bs-theme="<?= Session::get('theme', 'light') ?>"
    :data-bs-theme="theme"
    <?= !$required ?: 'required' ?>
    class="form-select"
    id="<?= $id ?>"
    <?= !$model ?: "x-model='{$model}'" ?>
    name="<?= $name ?>">
    <option value="" selected disabled>Selecciona una opción</option>
    <?php foreach ($options as $option) : ?>
      <option
        <?= !@$option['selected'] ?: 'selected' ?>
        value="<?= @$option['value'] ?>">
        <?= @$option['slot'] ?>
      </option>
    <?php endforeach ?>
  </select>
  <label for="<?= $id ?>">
    <?php if ($required) : ?>
      <sub class="text-danger fs-1">*</sub>
    <?php endif ?>
    <?= $label ?>
  </label>
</div>
