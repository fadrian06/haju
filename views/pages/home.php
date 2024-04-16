<?php

use App\Models\Department;
use App\Models\User;
use App\ValueObjects\Appointment;

/**
 * @var User $user
 * @var int $usersNumber
 * @var int $patientsNumber
 * @var int $departmentsNumber
 * @var int $consultationsNumber
 * @var Department $department
 */

?>

<section class="single_element">
  <div class="quick_activity_wrap">
    <?php if ($user->appointment->isHigherThan(Appointment::Coordinator)) : ?>
      <a href="./usuarios" class="single_quick_activity d-flex">
        <img class="icon" src="./assets/img/icons/man.svg" />
        <div class="count_content">
          <h3><?= $usersNumber ?></h3>
          <p>Usuario<?= $usersNumber !== 1 ? 's' : '' ?></p>
        </div>
      </a>
      <?php if ($user->appointment === Appointment::Director && $department->name === 'Estadística') : ?>
        <a href="./departamentos" class="single_quick_activity d-flex">
          <img class="icon" src="./assets/img/icons/cap.svg" />
          <div class="count_content">
            <h3><?= $departmentsNumber ?></h3>
            <p>Departamento<?= $departmentsNumber !== 1 ? 's' : '' ?></p>
          </div>
        </a>
      <?php endif ?>
    <?php endif ?>
    <?php if ($user->appointment->isHigherThan(Appointment::Secretary)) : ?>
      <a href="./pacientes" class="single_quick_activity d-flex">
        <img class="icon" src="./assets/img/icons/wheel.svg" />
        <div class="count_content">
          <h3><?= $patientsNumber ?></h3>
          <p>Paciente<?= $patientsNumber !== 1 ? 's' : '' ?></p>
          <p><?= $consultationsNumber ?> Consulta<?= $consultationsNumber === 1 ? '' : 's' ?></p>
        </div>
      </a>
    <?php endif ?>
  </div>
</section>
<?php $user->appointment->isHigherThan(Appointment::Coordinator) && $department->name === 'Estadística' && render('components/charts') ?>
<?php $user->appointment === Appointment::Coordinator && $department->name === 'Estadística' && render('components/reports') ?>
