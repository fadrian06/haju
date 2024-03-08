<?php

use App\Models\Gender;
use App\Models\User;

/**
 * @var User $user
 * @var ?string $error
 * @var ?string $message
 */

?>

<section class="mb-4 d-flex px-0 align-items-center justify-content-between">
  <h2 class="m-0">Editar perfil</h2>
  <a href="<?= route('/perfil') ?>" class="btn btn-primary rounded-pill d-flex align-items-center">
    <span class="px-2">Volver</span>
    <i class="px-2 ti-back-left"></i>
  </a>
</section>
<form method="post" enctype="multipart/form-data" class="d-flex px-0 flex-column align-items-center">
  <section class="white_box">
    <?php $error && render('components/notification', ['type' => 'error', 'text' => $error]) ?>
    <?php $message && render('components/notification', ['type' => 'message', 'text' => $message]) ?>
    <h3>Información básica</h3>
    <div class="row mt-4">
      <!-- <label class="col-md-3 pe-0 position-relative pointer">
        <img class="img-fluid" src="<?= $user->avatar?->asString() ?? asset('img/user.jpg') ?>" />
        <div class="fileupload btn">
          <i class="ti-plus text-white"></i>
          <input name="avatar" class="d-none" type="file" />
        </div>
      </label> -->
      <fieldset class="mt-4 mt-md-0 col-md row row-cols-md-2">
        <div class="col-md mt-3 form-floating">
          <input value="<?= $user->firstName ?>" required id="first_name" name="first_name" class="form-control" placeholder="Nombre" />
          <label for="first_name">Nombre</label>
        </div>
        <div class="col-md mt-3 form-floating">
          <input value="<?= $user->lastName ?>" required id="last_name" name="last_name" class="form-control" placeholder="Apellido" />
          <label for="last_name">Apellido</label>
        </div>
        <div class="col-md mt-3 form-floating">
          <input value="<?= $user->birthDate->getWithDashes() ?>" required type="date" id="birth_date" name="birth_date" class="form-control" placeholder="Fecha de nacimiento" />
          <label for="birth_date">Fecha de nacimiento</label>
        </div>
        <div class="col-md mt-3 form-floating">
          <select name="gender" id="gender" required class="form-select">
            <?php foreach (Gender::cases() as $gender) : ?>
              <option value="<?= $gender->value ?>" <?= $gender === $user->gender ? 'selected' : '' ?>>
                <?= $gender->value ?>
              </option>
            <?php endforeach ?>
          </select>
          <label for="gender">Género</label>
        </div>
      </fieldset>
    </div>
  </section>
  <section class="white_box mt-4">
    <h3>Información de contacto</h3>
    <fieldset class="row mt-4">
      <div class="col-12 mb-md-4 form-floating">
        <textarea name="address" id="address" placeholder="Dirección" rows="1" class="form-control"><?= $user->address ?></textarea>
        <label for="address">Dirección</label>
      </div>
      <div class="col-md mt-3 mt-md-0 form-floating">
        <input type="tel" value="<?= $user->phone ?>" name="phone" id="phone" placeholder="Teléfono" class="form-control" />
        <label for="phone">Teléfono</label>
      </div>
      <div class="col-md mt-3 mt-md-0 form-floating">
        <input type="email" value="<?= $user->email?->asString() ?>" name="email" id="email" placeholder="Correo electrónico" class="form-control" />
        <label for="email">Correo electrónico</label>
      </div>
    </fieldset>
  </section>
  <button class="btn btn-primary btn-lg mt-4 px-5 rounded-pill">Guardar</button>
</form>
