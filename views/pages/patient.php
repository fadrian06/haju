<?php

use App\Models\Patient;
use App\Models\User;

/**
 * @var User $user
 * @var Patient $patient
 * @var ?string $error
 * @var ?string $message
 */

?>

<section class="mb-4 d-flex px-0 align-items-center justify-content-between">
  <h2 class="m-0">Detalles del paciente</h2>
</section>
<article class="white_box px-0 pb-0 row align-items-center">
  <div class="profile-info col-md">
    <header class="profile-info__main py-2 py-md-0 my-2 my-md-0">
      <div class="row">
        <picture class="col-md-4 d-flex align-items-center justify-content-center mb-2 mb-md-0">
          <img style="max-height: 150px" class="img-fluid rounded-circle" src="https://unavatar.io/<?= $patient->firstName ?>" />
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
          <img src="<?= urldecode($patient->registeredBy->profileImagePath->asString()) ?>" class="img-fluid rounded-circle" />
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
  <ul class="nav nav-tabs row mx-0 px-0 mt-4">
    <li class="nav-item col px-0">
      <div class="row mx-0">
        <button class="nav-link col-sm px-0 active" data-bs-toggle="tab" data-bs-target="#visitas">
          Visitas médicas
        </button>
        <button class="nav-link col-sm px-0" data-bs-toggle="tab" data-bs-target="#visitas">
          Consultas externas
        </button>

      </div>
    </li>
  </ul>
</article>
<section class="tab-content mt-4 px-0">
  <article class="tab-pane fade show active" id="visitas">
    <div class="row mx-0">
      <article class="col-md px-0 pe-md-2">
        <div class="white_box">
          <h3>Visitas médicas</h3>
          <ul class="timeline mt-4">
            <?php foreach (range(1, 3) as $_) : ?>
              <li class="timeline-item">
                <a href="#" target="_blank">
                  <strong class="text-secondary">International College of Medical Science (UG)</strong>
                  <span class="text-black-50 fw-semibold">MBBS</span>
                  <time class="small text-black-50">2001 - 2003</time>
                </a>
              </li>
            <?php endforeach ?>
          </ul>
        </div>
      </article>
      <article class="col-md px-0 ps-md-2 mt-4 mt-md-0">
        <div class="white_box">
          <h3>Destacadas</h3>
          <ul class="timeline mt-4">
            <?php foreach (range(1, 2) as $_) : ?>
              <li class="timeline-item">
                <a href="#" target="_blank">
                  <strong class="text-secondary">Consultant Gynecologist</strong>
                  <time class="small text-black-50">Jan 2014 - Present (4 years 8 months)</time>
                </a>
              </li>
            <?php endforeach ?>
          </ul>
        </div>
      </article>
    </div>
  </article>
</section>
