<?php

declare(strict_types=1);

use App\Models\Patient;
use App\Repositories\Domain\PatientRepository;
use App\ValueObjects\DateRange;
use flight\Container;

$lastMonth = (new DateTimeImmutable)->sub(new DateInterval('P1M'))->format('Y-m-d');
$currentDate = date('Y-m-d') . ' 23:59:59';
$range = DateRange::tryFrom($_GET['rango'] ?? '');
$pdo = Container::getInstance()->get(PDO::class);

$stmt = $pdo->query(<<<sql
  SELECT consultations.cause_id, consultation_causes.short_name,
  consultation_causes.variant, consultation_causes.extended_name,
  COUNT(patient_id) as consultations
  FROM consultations
  JOIN consultation_causes
  ON consultations.cause_id = consultation_causes.id
  WHERE registered_date BETWEEN :start_date AND :end_date
  GROUP BY cause_id
  LIMIT 5
sql);

$startDate = ((@$_GET['fecha_inicio'] ?: $range?->getDate()->format('Y-m-d'))
    ?: $lastMonth) . ' 00:00:00';

$endDate = (@$_GET['fecha_fin'] ?: $currentDate);

$params = [
  ':start_date' => $startDate,
  ':end_date' => $endDate,
];

$stmt->execute($params);
$patients = [];
$patientsByCause = [];

$frecuentCauses = array_map(
  static function (array $frecuentCause) use (&$patients, $pdo, &$patientsByCause): array {
    $stmt = $pdo->prepare("SELECT patient_id FROM consultations WHERE cause_id = ?");
    $stmt->execute([$frecuentCause['cause_id']]);

    $patientIds = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

    foreach ($patientIds as $id) {
      $patient = $patients[$id] ?? Container::getInstance()->get(PatientRepository::class)->getById(intval($id));
      $patients[$id] = $patient;

      $frecuentCause['patients'][] = [
        'id' => $patient->id,
        'fullName' => $patient->getFullName(),
      ];

      $causeName = ($frecuentCause['extended_name'] ?? $frecuentCause['short_name']) . ' ' . $frecuentCause['variant'];
      $patientsByCause[$causeName][] = $patient;
    }

    return $frecuentCause;
  },
  $stmt->fetchAll(PDO::FETCH_ASSOC)
);

$stmt = $pdo->query(<<<sql
  SELECT short_name, extended_name, variant, registered_date,
  COUNT(short_name) as consultations
  FROM (
    SELECT consultation_causes.short_name, consultation_causes.extended_name,
    consultation_causes.variant, date(consultations.registered_date) as registered_date
    FROM consultations
    JOIN consultation_causes
    ON consultations.cause_id = consultation_causes.id
    WHERE cause_id = :cause_id AND registered_date BETWEEN :start_date AND :end_date
    GROUP BY registered_date
    ORDER BY registered_date
  ) GROUP BY registered_date
sql);

$params = [
  ':cause_id' => $_GET['id_causa'] ?? $frecuentCauses[0]['cause_id'] ?? '',
  ':start_date' => (@$_GET['fecha_inicio'] ?: $range?->getDate()->format('Y-m-d'))
    ?: $lastMonth,
  ':end_date' => @$_GET['fecha_fin'] ?: $currentDate
];

$stmt->execute($params);
$frecuentCause = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<script>
  const frecuentCauses = JSON.parse('<?= json_encode($frecuentCauses) ?>')
  const frecuentCause = JSON.parse('<?= json_encode($frecuentCause) ?>')

  const since = new Date(`<?= explode(' ', $startDate)[0] ?>`).toLocaleDateString('es-VE', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  })

  const untilString = `<?= $endDate ?>`

  const until = new Date(untilString).toLocaleDateString('es-VE', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  })
</script>

<section class="mb-4 d-flex align-items-center">
  <h2>Resúmenes - </h2>
  <i class="ti-pie-chart ms-2 h2"></i>
  <i class="ti-bar-chart-alt ms-2 h2"></i>
  <span class="border border-dark flex-grow-1 ms-2 h2"></span>
</section>

<hr id="causas-mas-frecuentes" />

<div class="white_box">
  <h3 class="d-flex gap-3 align-items-center justify-content-between flex-wrap">
    <form class="row align-items-center row-gap-3" action="#causas-mas-frecuentes">
      <span class="col-md-12">Causas de consulta más frecuentes</span>

      <?php

      Flight::render('components/input-group', [
        'name' => 'fecha_inicio',
        'placeholder' => 'Desde',
        'type' => 'date',
        'value' => $_GET['fecha_inicio'] ?? '',
        'cols' => 6,
        'required' => false,
      ]);

      Flight::render('components/input-group', [
        'name' => 'fecha_fin',
        'placeholder' => 'Hasta',
        'type' => 'date',
        'value' => $_GET['fecha_fin'] ?? $currentDate,
        'cols' => 6,
        'required' => false,
        'max' => $currentDate
      ]);

      ?>

      <div class="col-md-12 d-flex align-items-center justify-content-center flex-wrap gap-3">
        <?php foreach (DateRange::cases() as $index => $dateRange): ?>
          <?php Flight::render('components/input-group', [
            'name' => 'rango',
            'type' => 'radio',
            'checked' => ($_GET['rango'] ?? DateRange::Monthly->value) === $dateRange->value,
            'placeholder' => $dateRange->value,
            'value' => $dateRange->value,
          ]) ?>
        <?php endforeach ?>
      </div>

      <button class="btn btn-primary btn-lg px-5 rounded-pill col-md-12">
        Consultar
      </button>
    </form>
    <canvas class="w-100" id="frecuent-causes"></canvas>

    <h4>Por paciente:</h4>

    <dl id="frecuent-causes-patients-list">
      <?php foreach ($patientsByCause as $cause => $causePatients) : ?>
        <?php $added = [] ?>

        <dt><?= $cause ?></dt>
        <dd>
          <?= implode(', ', array_map(
            static fn (Patient $patient): string => "<a href='./pacientes/{$patient->id}'>{$patient->getFullName()}</a>",
            array_filter($causePatients, static function (Patient $patient) use (&$added): bool {
              if (array_key_exists($patient->id, $added)) {
                return false;
              }

              $added[$patient->id] = $patient;

              return true;
            },)
          )) ?>
        </dd>
      <?php endforeach ?>
    </dl>

    <button class="btn btn-primary btn-lg w-100" id="print-frecuent-causes">
      Imprimir
    </button>
  </h3>
</div>

<hr id="causa-mas-frecuente" />

<div class="mt-2 white_box">
  <h3 class="d-flex gap-3 align-items-center justify-content-between flex-wrap">
    <form class="row align-items-center row-gap-3" action="#causa-mas-frecuente">
      <span class="col-md-3">Casos de</span>

      <?php

      Flight::render('components/input-group', [
        'name' => 'id_causa',
        'type' => 'select',
        'placeholder' => 'Causa de consulta',
        'options' => array_map(static fn(array $cause): array => [
          'selected' => intval($_GET['id_causa'] ?? $frecuentCauses[0]['cause_id']) === intval($cause['cause_id']),
          'value' => $cause['cause_id'],
          'text' => $cause['extended_name'] ?? "{$cause['short_name']} {$cause['variant']}"
        ], $frecuentCauses),
        'cols' => 9,
        'margin' => 0
      ]);

      Flight::render('components/input-group', [
        'name' => 'fecha_inicio',
        'placeholder' => 'Desde',
        'type' => 'date',
        'value' => $_GET['fecha_inicio'] ?? '',
        'cols' => 6,
        'required' => false,
      ]);

      Flight::render('components/input-group', [
        'name' => 'fecha_fin',
        'placeholder' => 'Hasta',
        'type' => 'date',
        'value' => $_GET['fecha_fin'] ?? $currentDate,
        'cols' => 6,
        'required' => false,
        'max' => $currentDate,
      ]);

      ?>

      <div class="col-md-12 d-flex align-items-center justify-content-center flex-wrap gap-3">
        <?php foreach (DateRange::cases() as $index => $dateRange): ?>
          <?php Flight::render('components/input-group', [
            'name' => 'rango',
            'type' => 'radio',
            'checked' => ($_GET['rango'] ?? DateRange::Monthly->value) === $dateRange->value,
            'placeholder' => $dateRange->value,
            'value' => $dateRange->value
          ]) ?>
        <?php endforeach ?>
      </div>

      <button class="btn btn-primary btn-lg px-5 rounded-pill col-md-12">
        Consultar
      </button>
    </form>
    <canvas class="w-100" id="frecuent-cause"></canvas>
    <button class="btn btn-primary btn-lg w-100" id="print-frecuent-cause">
      Imprimir
    </button>
  </h3>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const printBtns = {
      frecuentCause: document.getElementById('print-frecuent-cause'),
      frecuentCauses: document.getElementById('print-frecuent-causes')
    }

    const charts = {
      frecuentCauses: new Chart(document.getElementById('frecuent-causes'), {
        type: 'bar',
        data: {
          labels: frecuentCauses.map(cause => `${cause.extended_name || cause.short_name} ${cause.variant || ''}`),
          datasets: [{
            label: 'Número de casos',
            data: frecuentCauses.map(cause => cause.consultations),
            backgroundColor: ['#364f6b', '#e6e6e6', '#2daab8', '#0d6efd', '#eff1f7'],
            borderColor: 'black',
          }]
        }
      }),
      frecuentCause: new Chart(document.getElementById('frecuent-cause'), {
        type: 'line',
        data: {
          labels: frecuentCause.map(cause => cause.registered_date),
          datasets: [{
            label: 'Número de casos',
            data: frecuentCause.map(cause => cause.consultations),
            backgroundColor: '#344f6b',
            borderColor: 'black'
          }]
        }
      })
    }

    function printCanvas(canvas, title = '') {
      const image = canvas.toDataURL('image/png', 1)

      const browserTab = open()
      const script = document.createElement('script')
      // script.innerText = 'setTimeout(print, 1000)'

      const patientsList = document.querySelector('#frecuent-causes-patients-list').outerHTML

      browserTab.document.write(`
        <?php include __DIR__ . '/frecuent-causes-template.php' ?>
        ${script.outerHTML}
      `)
    }

    printBtns.frecuentCauses.addEventListener(
      'click',
      () => printCanvas(charts.frecuentCauses.canvas, 'Causas de consulta más frecuentes')
    )

    printBtns.frecuentCause.addEventListener(
      'click',
      () => printCanvas(
        charts.frecuentCause.canvas,
        `Frecuencia de ${frecuentCause[0]?.short_name} ${frecuentCause[0]?.variant || ''}`
      )
    )
  })
</script>
