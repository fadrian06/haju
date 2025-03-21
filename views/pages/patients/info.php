<?php

declare(strict_types=1);

use App\Models\Patient;
use App\Models\User;

/**
 * @var User $user
 * @var Patient $patient
 */

$causes = [];

foreach ($patient->getConsultation() as $consultation) {
  $causeName = $consultation->cause->getFullName(abbreviated: false);

  $causes[$causeName] ??= 0;
  ++$causes[$causeName];
}

$causes = array_slice($causes, 0, 5, true);
$avatarUrl = "https://unavatar.io/github/$patient->firstName";

$patientAvatar = @file_get_contents($avatarUrl)
  ? $avatarUrl
  : './assets/img/user.jpg';

?>

<script>
  const causesNames = JSON.parse('<?= json_encode(array_keys($causes)) ?>')
  const causesCounters = JSON.parse('<?= json_encode(array_values($causes)) ?>')
</script>

<section class="mb-4 d-flex px-0 align-items-center justify-content-between">
  <h2 class="m-0">Detalles del paciente</h2>
</section>
<article class="white_box px-0 pb-0 row align-items-center">
  <div class="profile-info col-md">
    <header class="profile-info__main py-2 py-md-0 my-2 my-md-0">
      <div class="row">
        <picture class="col-md-4 d-flex align-items-center justify-content-center mb-2 mb-md-0">
          <img
            style="max-height: 150px"
            class="img-fluid rounded-circle"
            src="<?= $patientAvatar ?>" />
        </picture>
        <div class="col-md d-flex flex-column">
          <h4 class="h3">Paciente: <?= $patient->getFullName() ?></h4>
          <strong>Cédula: V-<?= $patient->idCard ?></strong>
          <small class="text">Genero: <?= $patient->gender->value ?></small>
          <small class="text-muted">Registrado el: <?= $patient->registeredDate ?></small>
        </div>
      </div>
      <div class="row pt-3 mt-3 justify-content-end align-items-center" style="border-top: 2px dashed #ccc">
        <picture class="col-4 d-none d-md-block text-center">
          <img
            src="<?= urldecode($patient->registeredBy->profileImagePath->asString()) ?>"
            class="img-fluid rounded-circle" />
        </picture>
        <div class="col">
          <h5>Registrado por:</h5>
          <div class="col">
            <h6><?= $patient->registeredBy->getFullName() ?></h6>
            <span>V-<?= $patient->registeredBy->idCard ?></span>
            <span class="d-block"><?= $patient->registeredBy->getParsedAppointment() ?></span>
            <small class="text-muted"><?= $patient->registeredBy->phone ?></small>
            <small class="text-muted">
              <?= $patient->registeredBy->email->asString() ?>
            </small>
          </div>
        </div>
      </div>
    </header>
  </div>
  <ul class="profile-info__secondary col-md-6">
    <li>
      <strong>Primer nombre:</strong>
      <span><?= $patient->firstName ?></span>
    </li>
    <li>
      <strong>Segundo nombre:</strong>
      <?php if ($patient->secondName) : ?>
        <span><?= $patient->secondName ?></span>
      <?php else : ?>
        <mark class="text-muted">No establecido</mark>
      <?php endif ?>
    </li>
    <li>
      <strong>Primer apellido:</strong>
      <span><?= $patient->firstLastName ?></span>
    </li>
    <li>
      <strong>Segundo apellido:</strong>
      <?php if ($patient->secondLastName) : ?>
        <span><?= $patient->secondLastName ?></span>
      <?php else : ?>
        <mark class="text-muted">No establecido</mark>
      <?php endif ?>
    </li>
    <li>
      <strong>Fecha de nacimiento:</strong>
      <span><?= $patient->birthDate ?></span>
    </li>
  </ul>
  <h3 class="mt-4 mb-0 py-3 text-center active border">
    <strong>Historial médico</strong>
  </h3>
  <ul class="nav nav-tabs row mx-0 px-0">
    <li class="nav-item col px-0">
      <div class="row mx-0">
        <button class="nav-link col-sm px-0 active" data-bs-toggle="tab" data-bs-target="#visitas">
          Visitas médicas
        </button>
        <button class="nav-link col-sm px-0" data-bs-toggle="tab" data-bs-target="#hospitalizaciones">
          Hospitalizaciones
        </button>
        <!-- <button class="nav-link col-sm px-0" data-bs-toggle="tab" data-bs-target="#">
          Antecedentes médicos
        </button> -->
        <!-- <button class="nav-link col-sm px-0" data-bs-toggle="tab" data-bs-target="#">
          Tratamientos
        </button> -->
      </div>
    </li>
  </ul>
</article>
<section class="tab-content mt-4 px-0">
  <article class="tab-pane fade show active" id="visitas">
    <div class="row mx-0">
      <article class="col-md px-0 pe-md-2">
        <div class="white_box">
          <h3>Visitas médicas <sub>(de la más reciente a la más antigua)</sub></h3>
          <ul class="timeline mt-4">
            <?php if (!$patient->hasConsultations()): ?>
              No hay consultas registradas
              <a href="./consultas/registrar">Registrar una</a>
            <?php endif ?>

            <?php foreach ($patient->getConsultation() as $consultation) : ?>
              <li class="timeline-item">
                <a target="_blank" data-bs-toggle="tooltip" title="<?= $consultation->cause->category->extendedName ?? $consultation->cause->category->shortName ?>">
                  <strong class="text-secondary"><?= $consultation->cause->getFullName() ?></strong>
                  <span class="text-black-50 fw-semibold"><?= $consultation->type->getDescription() ?></span>
                  <time class="small text-black-50"><?= $consultation->registeredDate ?></time>
                  <small class="small text-black-50">
                    Atendido por: <?= $consultation->doctor->getFullName() ?>
                  </small>
                </a>
              </li>
            <?php endforeach ?>
          </ul>
        </div>
      </article>
      <article class="col-md px-0 ps-md-2 mt-4 mt-md-0">
        <div class="white_box">
          <h3>Frecuentes</h3>
          <canvas id="frecuent-consultation-causes" style="width: 100%"></canvas>
        </div>
      </article>
    </div>
  </article>

  <article class="tab-pane fade" id="hospitalizaciones">
    <div class="row mx-0">
      <article class="col-md-6 px-0 pe-md-2">
        <div class="white_box">
          <h3>Histórico</h3>
          <ul class="timeline mt-4">
            <?php foreach ($patient->getHospitalization() as $hospitalization) : ?>
              <li class="timeline-item">
                <strong class="text-secondary">Hospitalización #<?= $hospitalization->id ?></strong>
                <time class="small text-black-50"><?= $hospitalization->registeredDate ?></time>
                <span class="text-black-50 fw-semibold custom-badge status-<?= $hospitalization->isFinished() ? 'green' : 'red' ?>">
                  <?= $hospitalization->isFinished() ? 'Finalizada' : 'No finalizada' ?>
                </span>
                <?php if (!$hospitalization->isFinished()) : ?>
                  <a
                    class="btn btn-primary w-100"
                    href="./hospitalizaciones/<?= $hospitalization->id ?>/alta">
                    Dar de alta
                  </a>
                <?php else: ?>
                  <span><?= $hospitalization->diagnoses ?></span>
                <?php endif ?>
              </li>
            <?php endforeach ?>
          </ul>
        </div>
      </article>
    </div>
  </article>
</section>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    new Chart(document.getElementById('frecuent-consultation-causes'), {
      type: 'pie',
      data: {
        labels: causesNames,
        datasets: [{
          data: causesCounters,
          backgroundColor: ['#364f6b', '#e6e6e6', '#2daab8', '#0d6efd', '#eff1f7'],
          borderColor: 'black'
        }]
      }
    });
  })
</script>
