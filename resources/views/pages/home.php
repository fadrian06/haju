<?php

declare(strict_types=1);

use App\Models\Department;
use App\Models\User;
use App\ValueObjects\Appointment;

/**
 * @var User $user
 * @var Department $department
 */
assert(isset($user) && $user instanceof User, new Error('User not set'));
assert(isset($department) && $department instanceof Department, new Error('Department not set'));
$usersNumber = isset($usersNumber) ? intval($usersNumber) : throw new Error('Users number not set');
$patientsNumber = isset($patientsNumber) ? intval($patientsNumber) : throw new Error('Patients number not set');
$departmentsNumber = isset($departmentsNumber) ? intval($departmentsNumber) : throw new Error('Departments number not set');
$consultationsNumber = isset($consultationsNumber) ? intval($consultationsNumber) : throw new Error('Consultations number not set');
$doctorsNumber = isset($doctorsNumber) ? intval($doctorsNumber) : throw new Error('Doctors number not set');

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
      <?php if ($user->appointment === Appointment::Director) : ?>
        <a href="./departamentos" class="single_quick_activity d-flex">
          <img class="icon" src="./assets/img/icons/hospital-o.svg" />
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
      <a href="./doctores" class="single_quick_activity d-flex">
        <img class="icon" src="./assets/img/icons/cap.svg" />
        <div class="count_content">
          <h3><?= $doctorsNumber ?></h3>
          <p>Doctor<?= $doctorsNumber !== 1 ? 'es' : '' ?></p>
        </div>
      </a>
    <?php endif ?>
  </div>
</section>

<?php

if ($user->appointment->isHigherThan(Appointment::Coordinator) && $department->isStatistics()) {
  Flight::render('components/charts');
  Flight::render('components/reports');
}

?>
