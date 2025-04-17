<?php

declare(strict_types=1);

use App\Models\Patient;

/**
 * @var array{cause: array{short_name: string}, patient: Patient} $epidemic
 */
assert(
  isset($epidemic)
    && is_array($epidemic)
    && array_key_exists('cause', $epidemic)
    && array_key_exists('patient', $epidemic),
  new Error('Epidemic alert data is not set')
);

?>

<marquee behavior="alternate" class="bg-warning py-2 p-5 fw-bold fs-3 fixed-bottom">
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
