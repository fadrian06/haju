<?php

use App\Models\User;
use App\ValueObjects\Appointment;

/**
 * @var User $user
 */

?>

<section class="mb-4 d-flex px-0 align-items-center justify-content-between">
  <h2 class="m-0">Mi perfil</h2>
  <a
    href="./perfil/editar"
    class="btn btn-primary rounded-pill d-flex align-items-center">
    <i class="px-2 ti-plus"></i>
    <span class="px-2">Editar perfil</span>
  </a>
</section>
<article class="white_box px-0 pb-0 row align-items-center">
  <picture class="col-md-2 d-flex align-items-center justify-content-center mb-2 mb-md-0">
    <img style="max-height: 150px" class="img-fluid rounded-circle" src="<?= urldecode($user->profileImagePath->asString()) ?>" />
  </picture>
  <div class="profile-info col-md align-items-center">
    <header class="profile-info__main py-2 py-md-0 my-2 my-md-0 d-flex flex-column">
      <h4 class="h3"><?= $user->getFullName() ?></h4>
      <small class="text-muted"><?= $user->getParsedAppointment() ?></small>
      <strong>Cédula: V-<?= $user->idCard ?></strong>
      <small class="text-muted">Registrado el: <?= $user->registeredDate ?></small>
    </header>
  </div>
  <ul class="profile-info__secondary col-md-6">
    <li>
      <strong>Teléfono:</strong>
      <a href="tel:+<?= $user->phone->toValidPhoneLink() ?>"><?= $user->phone ?></a>
    </li>
    <li>
      <strong>Correo:</strong>
      <a href="mailto:<?= $user->email->asString() ?>">
        <?= $user->email->asString() ?>
      </a>
    </li>
    <li>
      <strong>Dirección:</strong>
      <span><?= $user->address ?></span>
    </li>
    <li>
      <strong>Género:</strong>
      <span><?= $user->gender->value ?></span>
    </li>
    <li>
      <strong>Fecha de nacimiento:</strong>
      <span><?= $user->birthDate ?></span>
    </li>
  </ul>
  <ul class="nav nav-tabs row mx-0 px-0 mt-4">
    <li class="nav-item col px-0">
      <div class="row mx-0">
        <!-- <button class="nav-link col-sm px-0" data-bs-toggle="tab" data-bs-target="#about-cont">
          Acerca de
        </button> -->
        <button class="nav-link col-sm px-0 active" data-bs-toggle="tab" data-bs-target="#seguridad">
          Seguridad
        </button>
      </div>
    </li>
  </ul>
</article>
<section class="tab-content mt-4 px-0">
  <!-- <article class="tab-pane fade" id="about-cont">
    <div class="row mx-0">
      <article class="col-md px-0 pe-md-2">
        <div class="white_box">
          <h3>Información de Educación</h3>
          <ul class="timeline mt-4">
            <?php foreach (range(1, 1) as $_) : ?>
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
          <h3>Experience</h3>
          <ul class="timeline mt-4">
            <?php foreach (range(1, 1) as $_) : ?>
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
  </article> -->
  <section class="tab-pane fade show active row px-2" id="seguridad">
    <form method="post" action="./perfil#seguridad" class="col-md white_box">
      <h3 class="mb-4">Cambiar contraseña</h3>
      <div class="row">
        <?php
          render('components/input-group', [
            'variant' => 'input',
            'type' => 'password',
            'name' => 'old_password',
            'placeholder' => 'Contraseña anterior',
            'cols' => 12
          ]);

          render('components/input-group', [
            'type' => 'password',
            'name' => 'new_password',
            'placeholder' => 'Nueva contraseña',
            'cols' => 6
          ]);

          render('components/input-group', [
            'type' => 'password',
            'name' => 'confirm_password',
            'placeholder' => 'Confirmar contraseña',
            'cols' => 6
          ]);
        ?>
      </div>
      <div class="text-center">
        <button class="btn btn-primary rounded-pill">Actualizar</button>
      </div>
    </form>

    <form class="col-md"></form>
    <?php if ($user->appointment === Appointment::Director): ?>
      <div class="col-md-12 text-end">
        <button
          class="mt-4 btn btn-danger rounded-pill"
          data-bs-toggle="modal"
          data-bs-target="#disactivate-director-modal">
          <i class="ti-lock me-2"></i> Inhabilitar cuenta
        </button>
      </div>
    <?php endif ?>
  </section>
</section>

<?php render('components/confirmation', [
  'show' => false,
  'id' => 'disactivate-director-modal',
  'action' => "./usuarios/{$user->id}/desactivar",
  'title' => '¿Estás seguro que deseas inhabilitar tu cuenta?',
]) ?>
