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
 * @var ?string $list
 * @var ?bool $hidden
 * @var ?string $pattern
 * @var ?string $title
 */

$id = $name . random_int(0, mt_getrandmax());
$variant ??= 'input';
$required ??= true;
$min ??= 0;
$type ??= 'text';
$value ??= '';
$checked ??= false;
$multiple ??= false;
$readonly ??= false;
$hidden ??= false;
$pattern ??= null;
$title ??= null;
$margin ??= 4;
$max ??= null;
$oninput ??= null;

?>

<?php if ($variant === 'checkbox' || $variant === 'radio'): ?>
  <div class="form-check form-switch fs-6 d-flex gap-1 align-items-end">
    <input
      class="form-check-input"
      name="<?= $name ?>"
      type="<?= $variant ?>"
      id="<?= $id ?>"
      <?= $checked ? 'checked' : '' ?>
      <?= $hidden ? 'hidden' : '' ?>
      value="<?= $value ?>"
    />
    <label class="form-check-label" for="<?= $id ?>">
      <?= $placeholder ?>
    </label>
  </div>
<?php else: ?>
  <div class="col-md-<?= $cols ?? 6 ?> <?= $hidden ? 'd-none' : '' ?> form-floating mb-<?= $margin ?>">
    <?php if ($variant === 'input') : ?>
      <input
        style="height: 66px; <?= $type === 'date' ? 'padding-top: 1em; padding-bottom: 0' : '' ?>"
        type="<?= $type ?>"
        class="form-control <?= $readonly ? 'opacity-25' : '' ?>"
        <?= $required ? 'required' : '' ?>
        name="<?= $name ?>"
        id="<?= $id ?>"
        min="<?= $min ?>"
        <?= $max ? "max='$max'" : '' ?>
        placeholder="<?= $placeholder ?>"
        value="<?= $value ?>"
        <?= $readonly ? 'readonly' : '' ?>
        list="<?= $list ?? null ?>"
        <?= $pattern ? "pattern='$pattern'" : '' ?>
        <?= $title ? "data-bs-toggle='tooltip' title='$title'" : '' ?>
        <?= $oninput ? "oninput='$oninput'" : '' ?>
      />
    <?php elseif ($variant === 'textarea') : ?>
      <textarea
        class="form-control"
        <?= $required ? 'required' : '' ?>
        name="<?= $name ?>"
        id="<?= $id ?>"
        style="height: 66px"
        placeholder="<?= $placeholder ?>"><?= $value ?></textarea>
    <?php elseif ($variant === 'file'): ?>
      <input
        style="height: auto; padding-bottom: 0"
        type="file"
        class="form-control"
        <?= $required ? 'required' : '' ?>
        name="<?= $name ?>"
        id="<?= $id ?>"
      />
    <?php else: ?>
      <select
        style="border-color: rgb(241, 243, 245)"
        <?= $required ? 'required' : '' ?>
        class="form-select"
        name="<?= $name ?>"
        id="<?= $id ?>"
        placeholder="<?= $placeholder ?>"
        <?= $multiple ? 'multiple' : '' ?>>
        <option <?= !$value ? 'selected' : '' ?> disabled value="">
          Seleccione una opción
        </option>
        <?php foreach ($options as $option) : ?>
          <option
            <?= !empty($option['selected']) ? 'selected' : '' ?>
            value="<?= $option['value'] ?>">
            <?= $option['text'] ?>
          </option>
        <?php endforeach ?>
      </select>
    <?php endif ?>
    <label for="<?= $id ?>" style="<?= $labelStyle ?? '' ?>">
      <?= $placeholder . ($required ? '<sub class="text-danger ms-2" style="font-size: 2em">*</sub>' : '') ?>
    </label>
  </div>
<?php endif ?>
