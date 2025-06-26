<?php



use HAJU\Models\Patient;
use HAJU\Models\User;

/**
 * @var array<int, Patient> $patients
 * @var ?string $error
 * @var ?string $message
 * @var User $user
 */

$loggedUser = $user;
$onlyHospitalizedSwitchId = uniqid();

?>

<section class="container mb-4 d-flex align-items-center justify-content-between">
  <h2>Pacientes</h2>
  <a
    data-bs-toggle="modal"
    href="#registrar"
    class="btn btn-primary d-flex align-items-center">
    <i class="px-2 fa fa-plus"></i>
    <span class="px-2">Registrar paciente</span>
  </a>
</section>

<script>
  var patientsJsonParsed = JSON.parse(`<?= json_encode($patients, JSON_PARTIAL_OUTPUT_ON_ERROR) ?>`)
  var loggedUserJsonParsed = JSON.parse(`<?= json_encode($loggedUser, JSON_PARTIAL_OUTPUT_ON_ERROR) ?>`)
</script>

<section
  x-data='{
      showHospitalized: false,
      loggedUser: loggedUserJsonParsed,
      allPatients: patientsJsonParsed,
      patientName: "",

      get filteredPatients() {
        return this.allPatients.filter(patient => {
          const hasActiveHospitalizations = patient.numberOfUnfinishedHospitalizations > 0;

          const matchName = patient
            .fullName
            .toLowerCase()
            .includes(this.patientName.toLowerCase());

          if (this.showHospitalized) {
            return hasActiveHospitalizations && matchName;
          }

          return matchName;
        });
      },

      patientCanBeEditedByLoggedUser(patient) {
        return this.loggedUser.id === patient.registeredBy.id || this.loggedUser.isDirector;
      },
    }'
  class="container card card-body">
  <?php Flight::render('components/inputs/input', [
    'type' => 'search',
    'model' => 'patientName',
    'label' => 'Buscar por nombre...'
  ]) ?>
  <h3 class="my-4 fs-2">Filtrar por</h3>
  <div class="list-group mb-3">
    <label
      class="pe-auto list-group-item d-flex justify-content-between align-items-center"
      for="<?= $onlyHospitalizedSwitchId ?>">
      <span>Sólo hospitalizados</span>
      <div class="form-check form-switch">
        <input
          class="form-check-input fs-3"
          id="<?= $onlyHospitalizedSwitchId ?>"
          type="checkbox"
          x-model="showHospitalized" />
      </div>
    </label>
  </div>
  <div class="table-responsive">
    <table class="table text-center">
      <thead>
        <tr>
          <th></th>
          <th>Nombre completo</th>
          <th>Cédula</th>
          <th>Fecha de nacimiento</th>
          <th>Género</th>
          <th>Registrado por</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <template
          x-for="patient in (showHospitalized || patientName) ? filteredPatients : allPatients"
          x-key="patient.id">
          <tr>
            <form method="post" :action="`./pacientes/${patient.id}`">
              <td class="p-2">
                <a
                  class="btn btn-secondary btn-sm text-white"
                  :href="`./pacientes/${patient.id}`">
                  Detalles
                </a>
              </td>
              <td class="p-1">
                <input
                  :readonly="!patientCanBeEditedByLoggedUser(patient)"
                  placeholder="Nombre del paciente"
                  class="form-control"
                  required
                  name="full_name"
                  :value="patient.fullName" />
              </td>
              <td class="p-1">
                <input
                  :readonly="!patientCanBeEditedByLoggedUser(patient)"
                  type="number"
                  placeholder="Cédula del paciente"
                  class="form-control"
                  required
                  name="id_card"
                  :value="patient.idCard" />
              </td>
              <td class="p-1">
                <input
                  :readonly="!patientCanBeEditedByLoggedUser(patient)"
                  type="date"
                  placeholder="Fecha de nacimiento"
                  class="form-control"
                  required
                  name="birth_date"
                  :value="patient.birthDate" />
              </td>
              <td x-text="patient.gender"></td>
              <td
                :title="patient.registeredBy.fullName"
                x-text="patient.registeredBy.firstName">
              </td>
              <td class="p-2">
                <div class="btn-group">
                  <button
                    x-show="patientCanBeEditedByLoggedUser(patient)"
                    class="btn btn-sm btn-primary text-white">
                    Actualizar
                  </button>
                  <a
                    x-show="patient.canBeDeleted"
                    :href="`./pacientes/${patient.id}/eliminar`"
                    class="btn btn-sm btn-danger text-white">
                    Eliminar
                  </a>
                </div>
              </td>
            </form>
          </tr>
        </template>
      </tbody>
    </table>
  </div>
</section>

<?php

Flight::render('forms/patient-register', ['action' => './pacientes#registrar']);
