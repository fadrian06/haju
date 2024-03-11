<?php

use App\Models\User;

/** @var User $user */

?>

<h1>Seleccione un departamento:</h1>
<?php foreach ($user->getDepartment() as $department): ?>
  <a href="<?= route('/departamento/seleccionar/@id', ['id' => $department->getId()]) ?>" class="mt-3 col-sm-2 col-md-3 col-lg-4">
    <article class="btn btn-outline-light card card-body text-center shadow">
      <img width="50%" class="mx-auto mb-3" src="<?= asset('img/department.png') ?>" />
      <h2><?= $department->name ?></h2>
    </article>
  </a>
<?php endforeach ?>
