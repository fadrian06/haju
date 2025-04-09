<?php

declare(strict_types=1);

$id = uniqid();
$required ??= true;
$name ??= '';
$type ??= 'text';
$label ??= '';
$inputStyle ??= '';
$model ??= '';
$pattern ??= '';
$title ??= '';
$value ??= '';
$readonly ??= false;

$typeHasOneWord = str_contains(
  str_replace(['?', ':'], ' ', strval($type)),
  ' '
);

$valueHasOneWord = str_contains(
  str_replace(['?', ':'], ' ', strval($value)),
  ' '
);

?>

<div class="form-floating">
  <input
    style="<?= $inputStyle ?>"
    type="<?= $type ?>"
    :type="<?= $typeHasOneWord ? $type : "typeof {$type} !== 'undefined' ? {$type} : '{$type}'" ?>"
    id="<?= $id ?>"
    class="form-control"
    placeholder=" "
    name="<?= $name ?>"
    <?= !$required ?: 'required' ?>
    <?= !$model ?: "x-model='{$model}'" ?>
    <?= !$pattern ?: "pattern='{$pattern}'" ?>
    <?= !$pattern ?: ":pattern='{$pattern}'" ?>
    <?= !$readonly ?: 'readonly' ?>
    title="<?= $title ?>"
    value="<?= $value ?>"
    :value="<?= $valueHasOneWord ? $value : "typeof {$value} !== 'undefined' ? {$value} : '{$value}'" ?>" />
  <label for="<?= $id ?>">
    <?php if ($required) : ?>
      <sub class="text-danger fs-1">*</sub>
    <?php endif ?>
    <?= $label ?>
  </label>
</div>
