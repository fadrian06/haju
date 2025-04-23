<?php

declare(strict_types=1);

use HAJU\Models\Department;
use HAJU\Models\User;
use HAJU\Enums\Appointment;
use Leaf\Http\Session;

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
      <a
        href="./usuarios"
        class="single_quick_activity d-flex text-bg-<?= Session::get('theme', 'light') ?>"
        :class="`text-bg-${theme}`">
        <img class="icon" src="./resources/icons/man.svg" />
        <div class="count_content">
          <h3><?= $usersNumber ?></h3>
          <p>Usuario<?= $usersNumber !== 1 ? 's' : '' ?></p>
        </div>
      </a>
      <?php if ($user->appointment === Appointment::Director) : ?>
        <a
          href="./departamentos"
          class="single_quick_activity d-flex text-bg-<?= Session::get('theme', 'light') ?>"
          :class="`text-bg-${theme}`">
          <img class="icon" src="./resources/icons/hospital-o.svg" />
          <div class="count_content">
            <h3><?= $departmentsNumber ?></h3>
            <p>Departamento<?= $departmentsNumber !== 1 ? 's' : '' ?></p>
          </div>
        </a>
      <?php endif ?>
    <?php endif ?>
    <?php if ($user->appointment->isHigherThan(Appointment::Secretary)) : ?>
      <a
        href="./pacientes"
        class="single_quick_activity d-flex text-bg-<?= Session::get('theme', 'light') ?>"
        :class="`text-bg-${theme}`">
        <img class="icon" src="./resources/icons/wheel.svg" />
        <div class="count_content">
          <h3><?= $patientsNumber ?></h3>
          <p>Paciente<?= $patientsNumber !== 1 ? 's' : '' ?></p>
          <p><?= $consultationsNumber ?> Consulta<?= $consultationsNumber === 1 ? '' : 's' ?></p>
        </div>
      </a>
      <a
        href="./doctores"
        class="single_quick_activity d-flex text-bg-<?= Session::get('theme', 'light') ?>"
        :class="`text-bg-${theme}`">
        <img class="icon" src="./resources/icons/cap.svg" />
        <div class="count_content">
          <h3><?= $doctorsNumber ?></h3>
          <p>Doctor<?= $doctorsNumber !== 1 ? 'es' : '' ?></p>
        </div>
      </a>
    <?php endif ?>
  </div>
</section>
<?php $user->appointment->isHigherThan(Appointment::Coordinator)
  && $department->isStatistics()
  && Flight::render('components/charts')
?>

<?php $user->appointment->isHigherThan(Appointment::Coordinator)
  && $department->isStatistics()
  && Flight::render('components/reports')
?>
