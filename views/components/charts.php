<?php

$frecuentCauses = App::db()->instance()->query(<<<sql
  SELECT consultations.cause_id, consultation_causes.short_name,
  consultation_causes.variant, consultation_causes.extended_name,
  COUNT(patient_id) as consultations
  FROM consultations
  JOIN consultation_causes
  ON consultations.cause_id = consultation_causes.id
  WHERE registered_date BETWEEN '2024-04-01' AND CURRENT_TIMESTAMP
  GROUP BY cause_id
  LIMIT 5
sql)->fetchAll(PDO::FETCH_ASSOC);

$stmt = App::db()->instance()->query(<<<sql
  SELECT short_name, extended_name, variant, registered_date,
  COUNT(short_name) as consultations
  FROM (
    SELECT consultation_causes.short_name, consultation_causes.extended_name,
    consultation_causes.variant, date(consultations.registered_date) as registered_date
    FROM consultations
    JOIN consultation_causes
    ON consultations.cause_id = consultation_causes.id
    WHERE cause_id = :cause_id AND registered_date BETWEEN '2024-04-01' AND CURRENT_TIMESTAMP
    GROUP BY registered_date
    ORDER BY registered_date
  ) GROUP BY registered_date
sql);

$stmt->execute([$_GET['id_causa'] ?? $frecuentCauses[0]['cause_id'] ?? '']);
$frecuentCause = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$frecuentCauses) {
  return;
}

?>

<script>
  const frecuentCauses = JSON.parse('<?= json_encode($frecuentCauses) ?>')
  const frecuentCause = JSON.parse('<?= json_encode($frecuentCause) ?>')
</script>

<section class="mb-4 d-flex align-items-center">
  <h2>Resúmenes - </h2>
  <i class="ti-pie-chart ms-2 h2"></i>
  <i class="ti-bar-chart-alt ms-2 h2"></i>
  <span class="border border-dark flex-grow-1 ms-2 h2"></span>
</section>

<div class="white_box">
  <h3>Causas de consulta más frecuentes</h3>
  <canvas class="w-100" id="frecuent-causes"></canvas>
</div>

<div class="mt-2 white_box">
  <h3 class="d-flex gap-3 align-items-center justify-content-between flex-wrap">
    <span>Casos de</span>
    <form class="row align-items-center row-gap-3">
      <?php render('components/input-group', [
        'name' => 'id_causa',
        'variant' => 'select',
        'placeholder' => 'Causa de consulta',
        'options' => array_map(static fn(array $cause): array => [
          'selected' => ($_GET['id_causa'] ?? $frecuentCauses[0]['cause_id']) == $cause['cause_id'],
          'value' => $cause['cause_id'],
          'text' => $cause['extended_name'] ?? $cause['short_name'] . $cause['variant']
        ], $frecuentCauses),
        'cols' => 6,
        'margin' => 0
      ]) ?>
      <button class="btn btn-primary btn-lg px-5 rounded-pill col-md-auto">
        Consultar
      </button>
    </form>
    <canvas class="w-100" id="frecuent-cause"></canvas>
  </h3>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    new Chart(document.getElementById('frecuent-causes'), {
      type: 'bar',
      data: {
        labels: frecuentCauses.map(cause => (cause.extended_name || cause.short_name) + ' ' + (cause.variant || '')),
        datasets: [{
          label: 'Número de casos',
          data: frecuentCauses.map(cause => cause.consultations),
          backgroundColor: ['#364f6b', '#e6e6e6', '#2daab8', '#0d6efd', '#eff1f7'],
          borderColor: 'black'
        }]
      }
    })

    new Chart(document.getElementById('frecuent-cause'), {
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
  })
</script>
