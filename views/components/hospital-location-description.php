<div class="container py-5">
  <div class="row g-4">
    <!-- Encabezado -->
    <div class="col-12 text-center mb-4">
      <h2 class="display-5 text-success">Ubicación Estratégica</h2>
      <p class="lead">Conectados para servir mejor</p>
    </div>

    <!-- Mapa + Descripción -->
    <div class="col-lg-8">
      <div class="card shadow border-0 h-100">
        <div class="card-body p-0">
          <div class="ratio ratio-16x9 position-relative">
            <div class="weather">
              <?php renderComponent('hospital-weather') ?>
            </div>
            <?php renderComponent('hospital-location') ?>
          </div>
        </div>
        <div class="card-footer bg-light">
          <div class="row g-3 align-items-center">
            <div class="col-md-4">
              <div class="d-flex align-items-center">
                <i class="fas fa-map-marker-alt fa-2x text-success me-3"></i>
                <div>
                  <h5 class="mb-0">Dirección</h5>
                  <small>
                    Estado Mérida, Municipio Caracciolo Parra y Olmedo,
                    Parroquia Tucaní, Sector Andrés Bello, Calle Principal.
                  </small>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="d-flex align-items-center">
                <i class="fas fa-road fa-2x text-primary me-3"></i>
                <div>
                  <h5 class="mb-0">Accesos</h5>
                  <small>Cerca de la Carretera Panamericana</small>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="d-flex align-items-center">
                <i class="fas fa-car-side fa-2x text-info me-3"></i>
                <div>
                  <h5 class="mb-0">Estacionamiento</h5>
                  <small>Coches y Motocicletas</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Detalles Geográficos -->
    <div class="col-lg-4">
      <div class="card h-100 shadow border-0">
        <div class="card-header bg-success text-white">
          <h4 class="mb-0">
            <i class="fas fa-globe-americas me-2"></i>Nuestra Cobertura
          </h4>
        </div>
        <div class="card-body">
          <div class="d-grid gap-3">
            <div class="d-flex align-items-start">
              <i class="fas fa-mountain fa-2x text-secondary me-3"></i>
              <div>
                <h5>Área Natural</h5>
                <p class="mb-0 text-muted">
                  Rodeado de áreas verdes y lejos de zonas industriales
                </p>
              </div>
            </div>

            <div class="d-flex align-items-start">
              <i class="fas fa-ambulance fa-2x text-warning me-3"></i>
              <div>
                <h5>Ambulancia</h5>
                <p class="mb-0 text-muted">
                  Disponible las 24 horas, los 7 días de la semana.
                </p>
              </div>
            </div>

            <div class="d-flex align-items-start">
              <i class="fas fa-bus fa-2x text-danger me-3"></i>
              <div>
                <h5>Transporte Público</h5>
                <p class="mb-0 text-muted">
                  Terminal de Pasajeros a 5 minutos.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
