<?php

declare(strict_types=1);

use HAJU\Models\Department;

/**
 * @var Department $deprtament
 */

?>

<h2 class="m-0 d-flex gap-3 align-items-center">
  <span>Departamento de <?= $department ?></span>
  <a
    href="./departamento/seleccionar"
    class="btn btn-outline-primary btn-sm d-flex justify-content-center align-items-center"
    data-bs-toggle="tooltip"
    title="Cambiar de departamento">
    <span class="fa fa-arrow-right-arrow-left"></span>
  </a>
</h2>
