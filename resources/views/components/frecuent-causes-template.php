<?php



use HAJU\Models\Department;
use HAJU\Models\User;

/**
 * @var Department $department
 * @var User $user
 * @var int $consultationsTotal
 */

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width" />
  <title>${title}</title>
  <base href="<?= str_replace('index.php', '', $_SERVER['SCRIPT_NAME']) ?>" />
  <style>
    @import './resources/dist/guest.css';
  </style>
</head>

<body class="bg-white container">
  <div class="row row-gap-4 justify-content-between align-items-center">
    <div class="col-md-6">
      <figure class="d-grid align-items-center m-0 gap-3" style="grid-template-columns: auto auto">
        <img src="./resources/images/logo@light.png" class="img-fluid" />
        <figcaption>
          <h4 class="m-0">Hospital "Antonio José Uzcátegui"</h4>
        </figcaption>
      </figure>
    </div>
    <img src="./resources/images/gob.png" class="col-md-6 img-fluid" />
    <div class="col-md-12">
      <h4>
        Fecha de emisión: <u><?= date('d/m/Y H:i:sa') ?></u>
      </h4>
      <h4>
        Emitido por el departamento de: <u><?= $department->__toString() ?></u>
      </h4>
      <h4>
        Generado por: <u><?= $user->getFullName() . ' v' . $user->idCard ?></u>
      </h4>
    </div>
  </div>

  <h2 class="text-center mt-5">${title}</h2>
  <h4 class="text-center mb-5">
    Total de consultas: <u><?= $consultationsTotal ?></u>
  </h4>
  <div class="d-flex justify-content-end gap-3">
    <div class="input-group mb-3">
      <label style="flex-basis: 60px" class="input-group-text">DESDE</label>
      <input
        readonly
        value="${since}"
        class="form-control" />
    </div>
    <div class="input-group mb-3">
      <label style="flex-basis: 60px" class="input-group-text">HASTA</label>
      <input
        readonly
        value="${until}"
        class="form-control" />
    </div>
  </div>

  <div class="d-flex align-items-center">
    <img src="${images[0]}" width="75%" />
    <img src="${images[1]}" width="25%" />
  </div>

  <div class="my-5 d-flex justify-content-evenly fw-bolder">
    <div class="d-flex flex-column align-items-center">
      <u><?= str_repeat('&nbsp;', 100) ?></u>
      <span><?= $user->getFullName() ?></span>
      <small>Emisor</small>
    </div>
    <div class="d-flex flex-column align-items-center">
      <u><?= str_repeat('&nbsp;', 100) ?></u>
      <span><?= $user->getDirector()->getFullName() ?></span>
      <small>Director</small>
    </div>

  </div>
</body>

</html>
