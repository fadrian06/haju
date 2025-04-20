<?php

declare(strict_types=1);

use App\Enums\InputGroupType;

/**
 * @var array{value: string, text: string, selected?: bool}[] $options
 */
$name = isset($name) ? strval($name) : throw new Error('Name not found');
$type ??= 'text';

$placeholder = isset($placeholder)
  ? strval($placeholder)
  : throw new Error('Placeholder not found');

if ($type === 'select' && !isset($options)) {
  throw new Error('Options not found');
}

$options ??= [];
$required ??= true;
$min ??= 0;
$value ??= '';
$checked ??= false;
$multiple ??= false;
$readonly ??= false;
$hidden ??= false;
$pattern = isset($pattern) ? strval($pattern) : null;
$title = isset($title) ? strval($title) : null;
$margin ??= 4;
$max = isset($max) ? intval($max) : null;
$oninput ??= null;
$model ??= '';
$list ??= '';
$cols = isset($cols) ? intval($cols) : 6;
assert($cols > 0 && $cols <= 12);

$type = match (true) {
  is_string($type) => InputGroupType::from($type),
  $type instanceof InputGroupType => $type,
  default => InputGroupType::TEXT,
};

$id = $name . random_int(0, mt_getrandmax());

if (isset($variant)) {
  $error = new Error('DEPRECATED INPUT-GROUP COMPONENT PARAM: variant');

  $trace = implode("\n  ", array_map(
    // phpcs:ignore Generic.Files.LineLength.TooLong
    static fn(array $call): string => ($call['file'] ?? '') . ':' . ($call['line'] ?? ''),
    array_filter(
      $error->getTrace(),
      // phpcs:ignore Generic.Files.LineLength.TooLong
      static fn(array $call): bool => str_contains($call['file'] ?? '', 'views'),
    )
  ));

  file_put_contents(
    LOGS_PATH . '/deprecations.log',
    $error->getMessage() . PHP_EOL . $trace,
  );
} else {
  file_put_contents(LOGS_PATH . '/deprecations.log', '');
}

?>

<?php if ($type->isCheckbox() || $type->isRadio()): ?>
  <div class="form-check form-switch fs-6 d-flex gap-1 align-items-end">
    <input
      class="form-check-input"
      name="<?= $name ?>"
      type="<?= $type->value ?>"
      id="<?= $id ?>"
      <?= $checked ? 'checked' : '' ?>
      <?= $hidden ? 'hidden' : '' ?>
      <?= !$model ?: "x-model='{$model}'" ?>
      value="<?= $value ?>" />
    <label class="form-check-label" for="<?= $id ?>">
      <?= $placeholder ?>
    </label>
  </div>
<?php else: ?>
  <div class="col-md-<?= $cols ?> <?= $hidden ? 'd-none' : '' ?> form-floating mb-<?= $margin ?>">
    <?php if ($type->isTextarea()) : ?>
      <textarea
        class="form-control"
        <?= $required ? 'required' : '' ?>
        name="<?= $name ?>"
        id="<?= $id ?>"
        style="height: 66px"
        placeholder="<?= $placeholder ?>"><?= $value ?></textarea>
    <?php elseif ($type->isFile()): ?>
      <input
        style="height: auto; padding-bottom: 0"
        type="file"
        class="form-control"
        <?= $required ? 'required' : '' ?>
        name="<?= $name ?>"
        id="<?= $id ?>" />
    <?php elseif ($type->isSelect()) : ?>
      <select
        style="border-color: rgb(241, 243, 245)"
        <?= $required ? 'required' : '' ?>
        class="form-select"
        name="<?= $name ?>"
        id="<?= $id ?>"
        placeholder="<?= $placeholder ?>"
        <?= $multiple ? 'multiple' : '' ?>>
        <option
          <?= $value ?: 'selected' ?>
          disabled
          value="">
          Seleccione una opción
        </option>
        <?php foreach ($options as $option) : ?>
          <option
            <?= !@$option['selected'] ?: 'selected' ?>
            value="<?= $option['value'] ?>">
            <?= $option['text'] ?>
          </option>
        <?php endforeach ?>
      </select>
    <?php else: ?>
      <input
        style="height: 66px; <?= !$type->isDate() ?: 'padding-top: 1em; padding-bottom: 0' ?>"
        type="<?= $type->value ?>"
        class="form-control <?= $readonly ? 'opacity-25' : '' ?>"
        <?= $required ? 'required' : '' ?>
        name="<?= $name ?>"
        id="<?= $id ?>"
        min="<?= $min ?>"
        <?= $max === null ?: "max='{$max}'" ?>
        placeholder="<?= $placeholder ?>"
        value="<?= $value ?>"
        <?= !$readonly ?: 'readonly' ?>
        list="<?= $list ?>"
        <?= $pattern === null ?: "pattern='{$pattern}'" ?>
        <?= !$title ?: "data-bs-toggle='tooltip' title='{$title}'" ?>
        <?= !$oninput ?: "oninput='{$oninput}'" ?> />
    <?php endif ?>
    <label for="<?= $id ?>" style="<?= $labelStyle ?? '' ?>">
      <?= $placeholder ?>
      <?php if ($required) : ?>
        <sub class="text-danger ms-2" style="font-size: 2em">*</sub>
      <?php endif ?>
    </label>
  </div>
<?php endif ?>
