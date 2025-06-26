<?php



use HAJU\Enums\InputGroupType;

/**
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

$cols = isset($cols) ? intval($cols) : 12;
assert($cols > 0 && $cols <= 12);

$type = match (true) {
  isset($type) && is_string($type) => InputGroupType::from($type),
  isset($type) && $type instanceof InputGroupType => $type,
  default => InputGroupType::TEXT,
};

$id = $name . random_int(0, mt_getrandmax());

if (isset($variant)) {
  $error = new Error('DEPRECATED INPUT-GROUP COMPONENT PARAM: variant');

  $trace = join("\n  ", array_map(
    static fn(array $call): string => ($call['file'] ?? '') . ':' . ($call['line'] ?? ''),
    array_filter(
      $error->getTrace(),
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
$model ??= '';

?>

<?php if ($type === InputGroupType::CHECKBOX || $type === InputGroupType::RADIO) : ?>
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
<?php else : ?>
  <div class="col-md-<?= $cols ?> <?= $hidden ? 'd-none' : '' ?> form-floating mb-<?= $margin ?>">
    <?php if ($type === InputGroupType::TEXTAREA) : ?>
      <textarea
        class="form-control"
        <?= $required ? 'required' : '' ?>
        name="<?= $name ?>"
        id="<?= $id ?>"
        style="height: 66px"
        <?= !$model ?: "x-model='{$model}'" ?>
        placeholder="<?= $placeholder ?>"><?= $value ?></textarea>
    <?php elseif ($type === InputGroupType::FILE) : ?>
      <input
        style="height: auto; padding-bottom: 0"
        type="file"
        class="form-control"
        <?= $required ? 'required' : '' ?>
        name="<?= $name ?>"
        id="<?= $id ?>" />
    <?php elseif ($type === InputGroupType::SELECT) : ?>
      <select
        style="border-color: rgb(241, 243, 245)"
        <?= $required ? 'required' : '' ?>
        class="form-select"
        name="<?= $name ?>"
        id="<?= $id ?>"
        placeholder="<?= $placeholder ?>"
        <?= !$model ?: "x-model='{$model}'" ?>
        <?= $multiple ? 'multiple' : '' ?>>
        <option <?= !$value ? 'selected' : '' ?> disabled value="">
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
    <?php else : ?>
      <input
        style="height: 66px; <?= $type === 'date' ? 'padding-top: 1em; padding-bottom: 0' : '' ?>"
        type="<?= $type->value ?>"
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
        <?= !$model ?: "x-model='{$model}'" ?>
        <?= $pattern ? "pattern='$pattern'" : '' ?>
        <?= $title ? "data-bs-toggle='tooltip' title='$title'" : '' ?>
        <?= $oninput ? "oninput='$oninput'" : '' ?> />
    <?php endif ?>
    <label for="<?= $id ?>" style="<?= $labelStyle ?? '' ?>">
      <?= $placeholder ?>
      <?php if ($required) : ?>
        <sub class="text-danger ms-2" style="font-size: 2em">*</sub>
      <?php endif ?>
    </label>
  </div>
<?php endif ?>
