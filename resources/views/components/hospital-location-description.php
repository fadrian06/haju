<?php

declare(strict_types=1);

?>

<section class="container py-5">
  <div class="row row-cols-lg-2 gap-3">
    <header class="col-lg-12 text-center">
      <h2 class="display-5 text-success">Ubicación Estratégica</h2>
      <p class="lead">Conectados para servir mejor</p>
    </header>

    <div class="col-lg-7">
      <article class="card shadow-lg border-0 h-100">
        <header class="ratio ratio-16x9 position-relative">
          <?php Flight::render('components/hospital-location') ?>
          <div class="weather end-0" style="max-width: 100px; left: unset">
            <?php Flight::render('components/hospital-weather') ?>
          </div>
        </header>
        <footer class="card-footer h-100">
          <div class="row">
            <div class="col-md-5 d-flex gap-3">
              <i class="fa fa-map-marker-alt fa-2x text-success"></i>
              <div>
                <h5>Dirección</h5>
                <small>
                  Estado Mérida, Municipio Caracciolo Parra y Olmedo,
                  Parroquia Tucaní, Sector Andrés Bello, Calle Principal.
                </small>
              </div>
            </div>
            <div class="col-md-3 d-flex gap-3">
              <i class="fa fa-road fa-2x text-primary"></i>
              <div>
                <h5>Accesos</h5>
                <small>Cerca de la Carretera Panamericana</small>
              </div>
            </div>
            <div class="col-md-4 d-flex gap-3">
              <i class="fa fa-car-side fa-2x text-info"></i>
              <div>
                <h5>Estacionamiento</h5>
                <small>Coches y motocicletas</small>
              </div>
            </div>
          </div>
        </footer>
      </article>
    </div>

    <div class="col-lg-4">
      <article class="card h-100 shadow border-0">
        <h4 class="card-header text-bg-success bg-gradient mb-0">
          <i class="fas fa-globe-americas me-3"></i>
          Nuestra cobertura
        </h4>
        <footer class="card-footer h-100">
          <ul class="list-unstyled d-grid gap-3">
            <div class="d-flex align-items-center gap-3">
              <i class="fas fa-mountain fa-2x text-secondary"></i>
              <div>
                <h5>Área Natural</h5>
                <p class="mb-0 text-muted">
                  Rodeado de áreas verdes y lejos de zonas industriales
                </p>
              </div>
            </div>

            <div class="d-flex align-items-center gap-3">
              <i class="fas fa-ambulance fa-2x text-warning"></i>
              <div>
                <h5>Ambulancia</h5>
                <p class="mb-0 text-muted">
                  Disponible las 24 horas, los 7 días de la semana.
                </p>
              </div>
            </div>

            <div class="d-flex align-items-center gap-3">
              <i class="fas fa-bus fa-2x text-danger"></i>
              <div>
                <h5>Transporte Público</h5>
                <p class="mb-0 text-muted">
                  Terminal de Pasajeros a 5 minutos.
                </p>
              </div>
            </div>
          </ul>
        </footer>
      </article>
    </div>
  </div>
</section>
