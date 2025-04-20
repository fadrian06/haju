<?php

declare(strict_types=1);

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
    <div class="row justify-content-between align-items-center">
      <div class="col-md-6">
        <figure class="d-grid align-items-center m-0 gap-3" style="grid-template-columns: auto auto">
          <img src="./assets/img/logo@light.png" class="img-fluid" />
          <figcaption>
            <h4 class="m-0">Hospital "Antonio José Uzcátegui"</h4>
          </figcaption>
        </figure>
      </div>
      <img src="./assets/img/gob.png" class="col-md-6 img-fluid" />
    </div>

    <h2 class="text-center my-5">${title}</h2>
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

    <center>
      <img src="${image}" width="75%" />
    </center>

    <!-- <h1 class="text-center">Por paciente</h1>
    ${patientsList} -->
  </body>
</html>
