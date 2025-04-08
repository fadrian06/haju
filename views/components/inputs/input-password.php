<?php

declare(strict_types=1);

$required ??= true;
$isEdgeBrowser = str_contains(strtolower($_SERVER['HTTP_USER_AGENT']), 'edg');
$slot ??= '';
$label ??= $slot;
$model ??= '';
$pattern ??= '';
$title ??= '';
$value ??= '';

?>

<div class="form-floating position-relative" x-data="{ toggled: false }">
  <?php Flight::render('components/inputs/input', [
    'label' => $label,
    'type' => "toggled ? 'text' : 'password'",
    'name' => $name,
    'required' => $required,
    'model' => $model,
    'pattern' => $pattern,
    'title' => $title,
    'value' => $value,
  ]) ?>

  <?php if (!$isEdgeBrowser) : ?>
    <button
      style="right: .5em"
      type="button"
      @click="toggled = !toggled"
      class="btn btn-sm border-0 position-absolute top-50 translate-middle">
      <i class="fa" :class="toggled ? 'fa-eye-slash' : 'fa-eye'"></i>
    </button>
  <?php endif ?>
</div>
