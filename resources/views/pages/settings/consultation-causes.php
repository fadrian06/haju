<?php

use HAJU\Models\ConsultationCause;

/**
 * @var ConsultationCause[] $consultationCauses
 */

$groupByLimit = [
  'specified' => [],
  'unspecified' => []
];

foreach ($consultationCauses as $consultationCause) {
  $key = !$consultationCause->limit ? 'unspecified' : 'specified';
  $groupByLimit[$key][] = $consultationCause;
}

?>

<?php foreach (array_keys($groupByLimit) as $groupName) : ?>
  <form method="post" class="text-center">
    <h2 class="mb-4">Límite de casos semanales</h2>
    <div class="list-group">
      <?php foreach ($groupByLimit[$groupName] as $consultationCause) : ?>
        <label class="list-group-item d-flex align-items-center justify-content-between">
          <span class="user-select-none text-start"><?= $consultationCause->getFullName() ?></span>
          <?php Flight::render('components/input-group', [
            'type' => 'number',
            'name' => "limit_of[{$consultationCause->id}]",
            'placeholder' => 'Límite de casos semanales',
            'margin' => 0,
            'min' => 1,
            'required' => false,
            'cols' => 4,
            'value' => $consultationCause->limit ?? '',
            'labelStyle' => 'left: 0'
          ]) ?>
        </label>
      <?php endforeach ?>
    </div>
    <button class="btn btn-secondary mt-3">Guardar cambios</button>

    <hr />
  </form>
<?php endforeach ?>
