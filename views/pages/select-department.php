<?php

use App\Models\User;

/** @var User $user */

?>

<h1>Seleccione un departamento:</h1>
<?php foreach ($user->getDepartment() as $department): ?>
  <a
    href="./departamento/seleccionar/<?= $department->id ?>"
    class="<?= $department->isInactive() ? 'pe-none opacity-25' : '' ?> mt-3 col-sm-2 col-md-3 col-lg-4">
    <article class="btn btn-outline-light card card-body text-center shadow">
      <img class="img-fluid mb-3 rounded shadow-sm" src="<?= urldecode($department->iconFilePath->asString()) ?>" />
      <h2 class="fs-3"><?= $department->name ?></h2>
    </article>
  </a>
<?php endforeach ?>
