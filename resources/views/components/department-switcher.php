<?php

use HAJU\Models\Department;

/**
 * @var Department $department
 */

?>

<h2 class="m-0 d-flex gap-3 align-items-center">
  <strong>
    <span class="d-none d-md-inline">Departamento de</span>
    <?= $department ?>
  </strong>
  <a
    href="./departamento/seleccionar"
    class="btn btn-outline-primary btn-sm d-flex justify-content-center align-items-center"
    data-bs-toggle="tooltip"
    title="Cambiar de departamento">
    <span class="fa fa-arrow-right-arrow-left"></span>
  </a>
</h2>
