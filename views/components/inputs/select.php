<?php

declare(strict_types=1);

$id = uniqid();
$options ??= [];
$required ??= true;
$label ??= '';

static $isFirstRender = true;

?>

<div class="form-floating">
  <select
    <?= !$required ?: 'required' ?>
    class="form-select"
    id="<?= $id ?>">
    <option value="" selected disabled>Selecciona una opción</option>
    <?php foreach ($options as $option): ?>
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

<?php if ($isFirstRender) : ?>
  <style>
    .form-floating .form-select {
      min-height: 66px;
    }

    .form-select:focus {
      box-shadow: unset;
    }
  </style>

  <?php $isFirstRender = false ?>
<?php endif ?>
