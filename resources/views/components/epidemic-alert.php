<?php

use HAJU\Models\Patient;

/** @var array{cause: array{short_name: string}, patient: Patient} $epidemic */

?>

<marquee behavior="alternate" class="text-bg-warning py-2 p-5 fw-bold fs-3 fixed-bottom">
  <i class="ti-alert"></i>
  Alerta semanal de
  <strong class="text-uppercase fs-1 fw-bolder">
    <?= $epidemic['cause']['short_name'] ?>
    <a class="fs-6" href="./pacientes/<?= $epidemic['patient']->id ?>">
      (<?= @$epidemic['patient']?->getFullName() ?>)
    </a>
  </strong>
  <i class="ti-alert"></i>
</marquee>
