<?php

declare(strict_types=1);

use HAJU\Models\Department;
use HAJU\Models\User;
use HAJU\Enums\Appointment;

/**
 * @var User $user
 * @var int $usersNumber
 * @var int $patientsNumber
 * @var int $departmentsNumber
 * @var int $consultationsNumber
 * @var Department $department
 */

$cards = [
  [
    'title' => 'Usuarios',
    'icon' => 'fa-user-tie',
    'number' => $usersNumber,
    'link' => './usuarios',
    'background' => 'info',
  ],
  [
    'title' => 'Pacientes',
    'icon' => 'fa-wheelchair',
    'number' => $patientsNumber,
    'link' => './pacientes',
    'background' => 'success',
  ],
  [
    'title' => 'Consultas',
    'icon' => 'fa-stethoscope',
    'number' => $consultationsNumber,
    'link' => './consultas',
    'background' => 'warning',
  ],
  [
    'title' => 'Doctores',
    'icon' => 'fa-user-md',
    'number' => $doctorsNumber,
    'link' => './doctores',
    'background' => 'danger',
  ],
];

?>

<section class="row row-cols-md-2 g-3 mb-5">
  <?php foreach ($cards as $card) : ?>
    <div class="col">
      <a
        href="<?= $card['link'] ?>"
        class="card border-0 shadow-lg btn btn-outline-<?= $card['background'] ?> d-flex flex-row align-items-center gap-3">
        <span
          style="flex-basis: 100px"
          class="text-<?= $card['background'] ?>-emphasis fa <?= $card['icon'] ?> fa-4x"></span>
        <div class="d-flex flex-column text-start">
          <strong class="display-1 fw-bolder">
            <?= $card['number'] ?>
          </strong>
          <h2><?= $card['title'] ?></h2>
        </div>
      </a>
    </div>
  <?php endforeach ?>
</section>

<?php $user->appointment->isHigherThan(Appointment::Coordinator)
  && $department->isStatistics()
  && Flight::render('components/charts')
?>

<?php $user->appointment->isHigherThan(Appointment::Coordinator)
  && $department->isStatistics()
  && Flight::render('components/reports')
?>
