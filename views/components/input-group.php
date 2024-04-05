<?php

/**
 * @var ?int $cols
 * @var null|'input'|'textarea'|'select'|'file'|'checkbox' $variant
 * @var ?string $type
 * @var ?bool $required
 * @var ?bool $readonly
 * @var string $name
 * @var string $placeholder
 * @var ?string $value
 * @var ?bool $checked
 * @var array<int, array{value: string, text: string, selected?: bool}> $options
 * @var ?bool $multiple
 */

$id = $name . rand();
$variant ??= 'input';
$required ??= true;
$min ??= 0;
$type ??= 'text';
$value ??= '';
$checked ??= false;
$multiple ??= false;
$readonly ??= false;

?>

<?php if ($variant === 'checkbox'): ?>
  <div class="form-check form-switch fs-6">
    <input
      class="form-check-input"
      name="<?= $name ?>"
      type="checkbox"
      id="<?= $id ?>"
      <?= $checked ? 'checked' : '' ?>
    />
    <label class="form-check-label" for="<?= $id ?>">
      <?= $placeholder ?>
    </label>
  </div>
<?php else: ?>
  <div class="col-md-<?= $cols ?? 6 ?> form-floating mb-4">
    <?php if ($variant === 'input') : ?>
      <input
        <?= $type === 'date' ? 'style="height: auto"' : '' ?>
        type="<?= $type ?>"
        class="form-control <?= $readonly ? 'opacity-25' : '' ?>"
        <?= $required ? 'required' : '' ?>
        name="<?= $name ?>"
        id="<?= $id ?>"
        min="<?= $min ?>"
        placeholder="<?= $placeholder ?>"
        value="<?= $value ?>"
        <?= $readonly ? 'readonly' : '' ?>
      />
    <?php elseif ($variant === 'textarea') : ?>
      <textarea
        class="form-control"
        <?= $required ? 'required' : '' ?>
        name="<?= $name ?>"
        id="<?= $id ?>"
        style="height: 60px"
        placeholder="<?= $placeholder ?>"><?= $value ?></textarea>
    <?php elseif ($variant === 'file'): ?>
      <input
        style="height: auto"
        type="file"
        class="form-control"
        <?= $required ? 'required' : '' ?>
        name="<?= $name ?>"
        id="<?= $id ?>"
      />
    <?php else: ?>
      <select
        <?= $required ? 'required' : '' ?>
        class="form-select"
        name="<?= $name ?>"
        id="<?= $id ?>"
        placeholder="<?= $placeholder ?>"
        <?= $multiple ? 'multiple' : '' ?>>
        <option <?= !$value ? 'selected' : '' ?> disabled>Seleccione una opción</option>
        <?php foreach ($options as $option) : ?>
          <option
            <?= !empty($option['selected']) ? 'selected' : '' ?>
            value="<?= $option['value'] ?>">
            <?= $option['text'] ?>
          </option>
        <?php endforeach ?>
      </select>
    <?php endif ?>
    <label for="<?= $id ?>">
      <?= $placeholder . ($required ? '<sub class="text-danger ms-2" style="font-size: 2em">*</sub>' : '') ?>
    </label>
  </div>
<?php endif ?>
