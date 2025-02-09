<?php

/** @var array{cause: array{short_name: string}} $epidemic */

?>

<marquee behavior="alternate" class="bg-warning py-2 p-5 fw-bold fs-3 fixed-bottom">
  <i class="ti-alert"></i>
  Alerta semanal de
  <strong class="text-uppercase fs-1 fw-bolder"><?= $epidemic['cause']['short_name'] ?></strong>
  <i class="ti-alert"></i>
</marquee>
