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
$consultations = App::db()->instance()->query(<<<sql
  SELECT type, registered_date, cause_id FROM consultations
  WHERE registered_date BETWEEN '$startDate' AND '$endDate'
sql)->fetchAll(PDO::FETCH_ASSOC);

define('DAYS', $daysOfMonth);
$causeCounter = 1;

?>

<div class="w3-responsive w3-padding-large">
  <table style="width: 100%" class="w3-table w3-centered w3-bordered">
    <thead>
      <tr>
        <th rowspan="2">ENFERMEDADES</th>
        <th rowspan="2">Consultas</th>
        <th colspan="<?= DAYS ?>">DÍAS DEL MES</th>
        <th rowspan="2">TOTAL</th>
      </tr>
      <tr>
        <?php foreach (range(1, DAYS) as $day) : ?>
          <th><?= $day ?></th>
        <?php endforeach ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($causes as $cause) : ?>
        <?php if (!key_exists($cause['category']['id'], $categories)) : ?>
          <?php $categories[$cause['category']['id']] = $cause['category'] ?>
          <tr>
            <td colspan="<?= DAYS + 3 ?>" style="text-align: start">
              <?= $cause['category']['name']['extended'] ?? $cause['category']['name']['short'] ?>
            </td>
          </tr>
        <?php endif ?>
        <tr id="<?= $cause['id'] ?>">
          <th rowspan="3"><?= $causeCounter++ . '. ' . $cause['name']['short'] ?></th>
          <th>P</th>
          <?php foreach (range(1, DAYS) as $day) : ?>
            <td data-bs-toggle="tooltip" title="<?= "{$cause['name']['short']} ~ Primera vez ~ Día: " . $day ?>" id="cause-<?= $cause['id'] ?>_day-<?= $day ?>_type-P">0</td>
          <?php endforeach ?>
          <td data-bs-toggle="tooltip" title="<?= "{$cause['name']['short']} ~ Primera vez ~ Total" ?>">0</td>
        </tr>
        <tr>
          <th>S</th>
          <?php foreach (range(1, DAYS) as $day) : ?>
            <td data-bs-toggle="tooltip" title="<?= "{$cause['name']['short']} ~ Sucesiva ~ Día: " . $day ?>" id="cause-<?= $cause['id'] ?>_day-<?= $day ?>_type-S">0</td>
          <?php endforeach ?>
          <td data-bs-toggle="tooltip" title="<?= "{$cause['name']['short']} ~ Sucesiva ~ Total" ?>">0</td>
        </tr>
        <tr>
          <th>X</th>
          <?php foreach (range(1, DAYS) as $day) : ?>
            <td data-bs-toggle="tooltip" title="<?= "{$cause['name']['short']} ~ Asociada ~ Día: " . $day ?>" id="cause-<?= $cause['id'] ?>_day-<?= $day ?>_type-X">0</td>
          <?php endforeach ?>
          <td data-bs-toggle="tooltip" title="<?= "{$cause['name']['short']} ~ Asociada ~ Total" ?>">0</td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const consultations = JSON.parse('<?= json_encode($consultations) ?>')

    consultations.forEach(consultation => {
      const causeRow = document.getElementById(consultation.cause_id)

      let typeRow = causeRow

      if (consultation.type === 'S') {
        typeRow = causeRow.nextElementSibling
      } else {
        typeRow = causeRow.nextElementSibling.nextElementSibling
      }

      const registeredDate = new Date(consultation.registered_date)
      const day = registeredDate.getDate()
      const dayCell = document.getElementById(`cause-${consultation.cause_id}_day-${day}_type-${consultation.type}`)
      const totalCell = dayCell.parentElement.lastElementChild

      dayCell.innerText = parseInt(dayCell.innerText) + 1
      totalCell.innerText = parseInt(totalCell.innerText) + 1

      // console.log({causeId: consultation.cause_id, totalCell})
    })
  })
</script>
