<?php

use flight\template\View;

$api = App::get('fullRoot');
$causes = json_decode(file_get_contents("$api/api/causas-consulta/"), true);

/** @var array<int, \App\Models\ConsultationCauseCategory> */
$categories = [];

$monthYear = $_GET['fecha'] ?? null;

ob_start();
if ($monthYear) {
  [$year, $month] = explode('-', $monthYear);

  $daysOfMonth = match ($month) {
    '01', '03', '05', '07', '08', '10', '12' => 31,
    '04', '06', '09', '11' => 30,
    '02' => $year % 4 === 0 && ($year % 100 !== 0 || $year % 400 === 0)
      ? 29
      : 28
  };

  $startDate = (new View)->e("$monthYear-01");
  $endDate = (new View)->e("$monthYear-$daysOfMonth");
}

ob_end_clean();

$causeCounter = 1;
$categoryCounter = 1;
$printedParentCategories = [];

$consultations = App::db()->instance()->query(<<<sql
  SELECT type, cause_id FROM consultations
  WHERE registered_date BETWEEN '$startDate' AND '$endDate'
sql)->fetchAll(PDO::FETCH_ASSOC);

$typesByCause = [];

foreach ($consultations as $consultation) {
  $typesByCause[$consultation['cause_id']] ??= [
    'P' => 0,
    'S' => 0,
    'X' => 0
  ];

  $typesByCause[$consultation['cause_id']][$consultation['type']]++;
}

?>

<div class="p-1">
  <table style="width: 100%" class="w3-table w3-centered w3-bordered table table-hover">
    <thead>
      <tr>
        <th rowspan="3" colspan="2">ENFERMEDADES</th>
        <th colspan="5">NÚMERO DE CASOS</th>
      </tr>
      <tr>
        <th data-bs-toggle="tooltip" title="Primera vez">P</th>
        <th data-bs-toggle="tooltip" title="Sucesiva">S</th>
        <th data-bs-toggle="tooltip" title="Asociada">X</th>
        <th>P + X</th>
        <th rowspan="2">Acumulado del año</th>
      </tr>
      <tr>
        <th colspan="3"></th>
        <th>TOTAL</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($causes as $cause): ?>
        <?php if (!key_exists($cause['category']['id'], $categories)) : ?>
          <?php $categories[$cause['category']['id']] = $cause['category'] ?>
          <tr>
            <td class="fw-bold" colspan="7" style="text-align: start">
              <?php if ($cause['category']['parentCategory'] && !in_array($cause['category']['parentCategory'], $printedParentCategories)): ?>
                <?= $cause['category']['parentCategory']['name']['extended'] ?? $cause['category']['parentCategory']['name']['short'] ?>
                <br />
                <?php $printedParentCategories[] = $cause['category']['parentCategory'] ?>
              <?php endif ?>
              <?= $cause['category']['name']['extended'] ?? $cause['category']['name']['short'] ?>
            </td>
          </tr>
        <?php endif ?>
        <tr id="<?= $cause['id'] ?>">
          <th><?= $causeCounter++ ?></th>
          <th style="text-align: start"><?= $cause['name']['short'] ?></th>
          <td
            id="cause<?= $cause['id'] ?>-P"
            data-bs-toggle="tooltip"
            title="P">
          </td>
          <td
            id="cause<?= $cause['id'] ?>-S"
            data-bs-toggle="tooltip"
            title="S">
          </td>
          <td
            id="cause<?= $cause['id'] ?>-X"
            data-bs-toggle="tooltip"
            title="X">
          </td>
          <td
            id="cause<?= $cause['id'] ?>-PX"
            data-bs-toggle="tooltip"
            title="P + X">
          </td>
          <td
            id="cause<?= $cause['id'] ?>-A"
            data-bs-toggle="tooltip"
            title="Acumulado del año">
          </td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const typesByCause = JSON.parse('<?= json_encode($typesByCause) ?>')

    Object.keys(typesByCause).forEach(causeId => {
      const $p = document.querySelector(`#cause${causeId}-P`)
      const $s = document.querySelector(`#cause${causeId}-S`)
      const $x = document.querySelector(`#cause${causeId}-X`)
      const $px = document.querySelector(`#cause${causeId}-PX`)
      const $accumulated = document.querySelector(`#cause${causeId}-A`)

      $p.innerText = typesByCause[causeId].P
      $s.innerText = typesByCause[causeId].S
      $x.innerText = typesByCause[causeId].X
      $px.innerText = typesByCause[causeId].P + typesByCause[causeId].X
      $accumulated.innerText = typesByCause[causeId].P + typesByCause[causeId].S+ typesByCause[causeId].X
    })
  })
</script>
