<?php

/**
 * @var ?int $cols
 * @var null|'input'|'textarea'|'select'|'file' $variant
 * @var ?string $type
 * @var ?bool $required
 * @var string $name
 * @var string $placeholder
 * @var ?string $value
 * @var array<int, array{value: string, text: string}> $options
 */

$id = $name . rand();
$variant ??= 'input';
$required ??= true;
$min ??= 0;
$type ??= 'text';

?>

<div class="col-md-<?= $cols ?? 6 ?> form-floating mb-4">
  <?php if ($variant === 'input') : ?>
    <input
      <?= $type === 'date' ? 'style="height: auto"' : '' ?>
      type="<?= $type ?>"
      class="form-control"
      required="<?= $required ? 'true' : 'false' ?>"
      name="<?= $name ?>"
      id="<?= $id ?>"
      min="<?= $min ?>"
      placeholder="<?= $placeholder ?>"
    />
  <?php elseif ($variant === 'textarea') : ?>
    <textarea
      class="form-control"
      required="<?= $required ? 'true' : 'false' ?>"
      name="<?= $name ?>"
      id="<?= $id ?>"
      style="height: 60px"
      placeholder="<?= $placeholder ?>"><?= $value ?? '' ?></textarea>
  <?php elseif ($variant === 'file'): ?>
    <input
      style="height: auto"
      type="file"
      class="form-control"
      required="<?= $required ? 'true' : 'false' ?>"
      name="<?= $name ?>"
      id="<?= $id ?>"
    />
  <?php else: ?>
    <select
      required="<?= $required ? 'true' : 'false' ?>"
      class="form-select"
      name="<?= $name ?>"
      id="<?= $id ?>"
      placeholder="<?= $placeholder ?>">
      <option selected disabled>Seleccione una opción</option>
      <?php foreach ($options as $option) : ?>
        <option value="<?= $option['value'] ?>"><?= $option['text'] ?></option>
      <?php endforeach ?>
    </select>
  <?php endif ?>
  <label for="<?= $id ?>"><?= $placeholder ?></label>
</div>
