<?php

/** @var App\Models\User $user */
?>

<section class="mb-4 d-flex px-0 align-items-center justify-content-between">
  <h2 class="m-0">Mi perfil</h2>
  <a href="<?= route('/perfil/editar') ?>" class="btn btn-primary rounded-pill d-flex align-items-center">
    <i class="px-2 ti-plus"></i>
    <span class="px-2">Editar perfil</span>
  </a>
</section>
<article class="white_box px-0 pb-0 row align-items-center">
  <picture class="col-md-3 d-flex align-items-center justify-content-center mb-2 mb-md-0">
    <img style="max-height: 150px" class="img-fluid rounded-circle" src="<?= $user->avatar?->asString() ?? asset('img/client_img.png') ?>" />
  </picture>
  <div class="profile-info col-md align-items-center">
    <header class="profile-info__main py-2 py-md-0 my-2 my-md-0 d-flex flex-column">
      <h4 class="h3"><?= $user->getFullName() ?></h4>
      <small class="text-muted"><?= $user->getParsedRole() ?></small>
      <strong>ID: DR-<?= str_pad($user->getId(), 4, '0', STR_PAD_LEFT) ?></strong>
      <small class="text-muted">Registrado: <?= $user->getRegisteredDate() ?></small>
    </header>
  </div>
  <ul class="profile-info__secondary col-md-6">
    <li>
      <strong>Teléfono:</strong>
      <?php if ($user->phone) : ?>
        <a href="tel:+<?= $user->phone?->toValidPhoneLink() ?>"><?= $user->phone ?></a>
      <?php else : ?>
        <mark class="text-danger">No registrado</mark>
      <?php endif ?>
    </li>
    <li>
      <strong>Correo:</strong>
      <?php if ($user->email) : ?>
        <a href="mailto:<?= $user->email?->asString() ?>"><?= $user->email?->asString() ?></a>
      <?php else : ?>
        <mark class="text-danger">No registrado</mark>
      <?php endif ?>
    </li>
    <li>
      <strong>Dirección:</strong>
      <?php if ($user->address) : ?>
        <span><?= $user->address ?></span>
      <?php else : ?>
        <mark class="text-danger">No registrado</mark>
      <?php endif ?>
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
        <button class="nav-link col-sm px-0 active" data-bs-toggle="tab" data-bs-target="#security-cont">
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
  <section class="tab-pane fade show active row px-2" id="security-cont">
    <form method="post" class="col-md-6 white_box">
      <h3 class="mb-4">Cambiar contraseña</h3>
      <div class="form-floating mb-3">
        <input type="password" name="old_password" id="old-password" required placeholder="Contraseña anterior" class="form-control" />
        <label for="old-password">Contraseña anterior</label>
      </div>
      <div class="form-floating mb-3">
        <input type="password" name="new_password" id="new-password" required placeholder="Nueva contraseña" class="form-control" />
        <label for="new-password">Nueva contraseña</label>
      </div>
      <div class="form-floating mb-3">
        <input type="password" name="confirm_password" id="confirm-password" required placeholder="Confirmar contraseña" class="form-control" />
        <label for="confirm-password">Confirmar contraseña</label>
      </div>
      <div class="text-center">
        <button class="btn btn-primary rounded-pill">Actualizar</button>
      </div>
    </form>
  </section>
</section>
