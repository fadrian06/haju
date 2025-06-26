<?php

declare(strict_types=1);

use HAJU\Models\ConsultationCauseCategory;
use HAJU\Repositories\Domain\ConsultationCauseRepository;
use flight\Container;
use flight\template\View;

$causes = Container::getInstance()
  ->get(ConsultationCauseRepository::class)
  ->getAllWithGenerator();

$data = [];

foreach ($causes as $cause) {
  $data[] = (CAUSE_MAPPER)->__invoke($cause);
}

$causes = $data;

/** @var array<int, ConsultationCauseCategory> */
$categories = [];

$monthYear = $_GET['fecha'] ?? throw new Error('Fecha no proporcionada');

ob_start();

[$year, $month] = explode('-', (string) $monthYear);

$daysOfMonth = match ($month) {
  '01', '03', '05', '07', '08', '10', '12' => 31,
  '04', '06', '09', '11' => 30,
  '02' => $year % 4 === 0 && ($year % 100 !== 0 || $year % 400 === 0)
    ? 29
    : 28
};

$startDate = (new View())->e("{$monthYear}-01");
$endDate = (new View())->e("{$monthYear}-{$daysOfMonth}");

ob_end_clean();

$consultations = Container::getInstance()->get(PDO::class)->query(<<<sql
  SELECT type, registered_date, cause_id FROM consultations
  WHERE registered_date BETWEEN '{$startDate}' AND '{$endDate}'
sql)->fetchAll(PDO::FETCH_ASSOC);

$causeCounter = 1;
$printedParentCategories = [];

?>

<div class="p-1">
  <table style="width: 100%" class="w3-table w3-centered w3-bordered">
    <thead>
      <tr>
        <th rowspan="2">ENFERMEDADES</th>
        <th rowspan="2">Consultas</th>
        <th colspan="<?= $daysOfMonth ?>">DÍAS DEL MES</th>
        <th rowspan="2">TOTAL</th>
      </tr>
      <tr>
        <?php foreach (range(1, $daysOfMonth) as $day) : ?>
          <th><?= str_pad("{$day}", 2, '0', STR_PAD_LEFT) ?></th>
        <?php endforeach ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($causes as $cause) : ?>
        <?php if (!array_key_exists($cause['category']['id'], $categories)) : ?>
          <?php $categories[$cause['category']['id']] = $cause['category'] ?>
          <tr>
            <td
              class="fw-bold"
              colspan="<?= $daysOfMonth + 3 ?>"
              style="text-align: start">
              <?php if (is_array($cause['category']['parentCategory']) && !in_array($cause['category']['parentCategory'], $printedParentCategories, true)) : ?>
                <?= $cause['category']['parentCategory']['name']['extended'] ?? $cause['category']['parentCategory']['name']['short'] ?>
                <br />
                <?php $printedParentCategories[] = $cause['category']['parentCategory'] ?>
              <?php endif ?>
              <?= $cause['category']['name']['extended'] ?? $cause['category']['name']['short'] ?>
            </td>
          </tr>
        <?php endif ?>
        <tr id="<?= $cause['id'] ?>">
          <th rowspan="3">
            <?= $causeCounter++ . '. ' . $cause['name']['short'] ?>
          </th>
          <th>P</th>
          <?php foreach (range(1, $daysOfMonth) as $day) : ?>
            <td
              data-bs-toggle="tooltip"
              title="<?= "{$cause['name']['short']} ~ Primera vez ~ Día: " . $day ?>"
              id="cause-<?= $cause['id'] ?>_day-<?= $day ?>_type-P">
            </td>
          <?php endforeach ?>
          <td
            data-bs-toggle="tooltip"
            title="<?= "{$cause['name']['short']} ~ Primera vez ~ Total" ?>">
          </td>
        </tr>
        <tr>
          <th>S</th>
          <?php foreach (range(1, $daysOfMonth) as $day) : ?>
            <td
              data-bs-toggle="tooltip"
              title="<?= "{$cause['name']['short']} ~ Sucesiva ~ Día: " . $day ?>"
              id="cause-<?= $cause['id'] ?>_day-<?= $day ?>_type-S">
            </td>
          <?php endforeach ?>
          <td
            data-bs-toggle="tooltip"
            title="<?= "{$cause['name']['short']} ~ Sucesiva ~ Total" ?>">
          </td>
        </tr>
        <tr>
          <th>X</th>
          <?php foreach (range(1, $daysOfMonth) as $day) : ?>
            <td
              data-bs-toggle="tooltip"
              title="<?= "{$cause['name']['short']} ~ Asociada ~ Día: " . $day ?>"
              id="cause-<?= $cause['id'] ?>_day-<?= $day ?>_type-X">
            </td>
          <?php endforeach ?>
          <td
            data-bs-toggle="tooltip"
            title="<?= "{$cause['name']['short']} ~ Asociada ~ Total" ?>">
          </td>
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

      dayCell.innerText = parseInt(dayCell.innerText || 0) + 1
      totalCell.innerText = parseInt(totalCell.innerText || 0) + 1

      print()
    })
  })
</script>
