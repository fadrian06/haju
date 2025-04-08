<?php

declare(strict_types=1);

use App\Models\Patient;
use App\Models\User;

/**
 * @var array<int, Patient> $patients
 * @var ?string $error
 * @var ?string $message
 * @var User $user
 */

$loggedUser = $user;
$onlyHospitalizedSwitchId = uniqid();

?>

<section class="mb-4 d-inline-flex align-items-center justify-content-between">
  <h2>Pacientes</h2>
  <a
    data-bs-toggle="modal"
    href="#registrar"
    class="btn btn-primary rounded-pill d-flex align-items-center">
    <i class="px-2 ti-plus"></i>
    <span class="px-2">Registrar paciente</span>
  </a>
</section>

<?php if ($patients !== null) : ?>
  <section
    x-data='{
      showHospitalized: false,
      loggedUser: JSON.parse(`<?= json_encode($loggedUser) ?>`),
      allPatients: JSON.parse(`<?= json_encode($patients) ?>`),
      patientName: "",

      get filteredPatients() {
        return this.allPatients.filter(patient => {
          const hasActiveHospitalizations = patient.hospitalizations.some(hospitalization => hospitalization.isFinished);
          const matchName = patient.fullName.toLowerCase().includes(this.patientName.toLowerCase());

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
    class="white_box QA_section">
    <header class="list_header serach_field-area2 w-100 mb-3">
      <form @submit.prevent class="search_inner w-100">
        <input type="search" placeholder="Buscar por nombre..." x-model="patientName" />
        <button>
          <i class="ti-search fs-2"></i>
        </button>
      </form>

    </header>
    <h3 class="my-4 fs-2">Filtrar por</h3>
    <div class="list-group mb-3">
      <label
        class="pe-auto list-group-item d-flex justify-content-between align-items-center"
        for="<?= $onlyHospitalizedSwitchId ?>">
        <span class="user-select-none">Sólo hospitalizados</span>
        <div class="form-check form-switch">
          <input
            class="form-check-input fs-3"
            id="<?= $onlyHospitalizedSwitchId ?>"
            type="checkbox"
            x-model="showHospitalized" />
        </div>
      </label>
    </div>
    <div class="QA_table table-responsive">
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
<?php endif ?>

<?php

Flight::render('forms/patient-register', ['action' => './pacientes#registrar']);
