<?php

use HAJU\Controllers\DepartmentController;
use HAJU\Controllers\DoctorController;
use HAJU\Controllers\HomeController;
use HAJU\Controllers\LandingController;
use HAJU\Controllers\PatientController;
use HAJU\Controllers\ReportsController;
use HAJU\Controllers\SessionController;
use HAJU\Controllers\SettingsController;
use HAJU\Controllers\UserController;
use HAJU\Middlewares\AuthenticationMiddleware;
use HAJU\Middlewares\AuthorizationMiddleware;
use HAJU\Middlewares\EnsureCanEditDoctorMiddleware;
use HAJU\Middlewares\EnsureCanEditPatientMiddleware;
use HAJU\Middlewares\EnsureDepartmentIsActive;
use HAJU\Middlewares\EnsureOneSelectedDepartment;
use HAJU\Middlewares\EnsureOnlyAcceptOneDirector;
use HAJU\Middlewares\EnsureSelectedDepartmentIsNotStatistics;
use HAJU\Middlewares\EnsureUserIsNotAuthenticated;
use HAJU\Middlewares\LogLoginMiddleware;
use HAJU\Enums\Appointment;
use flight\Container;
use Leaf\Http\Session;

Flight::route('/', static function () {
  if (Session::has('userId')) {
    return true;
  }

  Container::getInstance()->get(LandingController::class)->showLanding();
});

Flight::route('/design-system', static function (): void {
  Flight::render('pages/design-system');
});

Flight::group('', static function (): void {
  Flight::route('/salir', [SessionController::class, 'logOut']);

  Flight::group('', static function (): void {
    Flight::route('GET /ingresar', [SessionController::class, 'showLogin']);
    Flight::route('POST /ingresar', [SessionController::class, 'handleLogin']);

    Flight::route(
      'GET /recuperar',
      [UserController::class, 'showPasswordReset']
    );

    Flight::route(
      'POST /recuperar',
      [UserController::class, 'handlePasswordReset']
    );
  });

  Flight::group('', static function (): void {
    Flight::route('GET /registrate', [UserController::class, 'showRegister']);

    Flight::route(
      'POST /registrate',
      [UserController::class, 'handleRegister']
    );
  }, [EnsureOnlyAcceptOneDirector::class]);
}, [EnsureUserIsNotAuthenticated::class]);

Flight::group('', function (): void {
  Flight::route('GET /hospitalizaciones', [PatientController::class, 'showHospitalizations']);
  Flight::route('GET /consultas', [PatientController::class, 'showConsultations']);

  Flight::route(
    'GET /departamento/seleccionar',
    [SessionController::class, 'showDepartments']
  );

  Flight::route(
    '/departamento/seleccionar/@id',
    [SessionController::class, 'saveChoice']
  )
    ->addMiddleware(LogLoginMiddleware::class);

  Flight::group('', function (): void {
    Flight::route('/', [HomeController::class, 'showIndex']);
    Flight::route('GET /perfil', [UserController::class, 'showProfile']);

    Flight::route(
      'POST /perfil',
      [UserController::class, 'handlePasswordChange']
    );

    Flight::route(
      'GET /perfil/editar',
      [UserController::class, 'showEditProfile']
    );

    Flight::route(
      'POST /perfil/editar',
      [UserController::class, 'handleEditProfile']
    );

    Flight::group('/doctores', function (): void {
      Flight::route('GET /', [DoctorController::class, 'showDoctors']);
      Flight::route('POST /', [DoctorController::class, 'handleRegister']);

      Flight::group('/@idCard', function (): void {
        Flight::route('GET /', [DoctorController::class, 'showEdit']);
        Flight::route('POST /', [DoctorController::class, 'handleEdition']);
      }, [EnsureCanEditDoctorMiddleware::class]);
    }, [new AuthorizationMiddleware(
      permitted: Appointment::Coordinator,
      blocked: null,
    )]);

    Flight::route('GET /pacientes', [PatientController::class, 'showPatients']);

    Flight::route(
      'GET /pacientes/@id:[0-9]+',
      [PatientController::class, 'showPatient']
    );

    Flight::route(
      'GET /pacientes/@id:[0-9]+/eliminar',
      [PatientController::class, 'deletePatient']
    );

    Flight::route(
      'POST /pacientes',
      [PatientController::class, 'handleRegister']
    );

    Flight::group('/consultas', function (): void {
      Flight::route(
        'GET /registrar',
        [PatientController::class, 'showConsultationRegister']
      );

      Flight::route(
        'POST /',
        [PatientController::class, 'handleConsultationRegister']
      );
    }, [EnsureSelectedDepartmentIsNotStatistics::class]);

    Flight::group('/hospitalizaciones', function (): void {
      Flight::route(
        'GET /registrar',
        [PatientController::class, 'showHospitalizationRegister']
      );

      Flight::route(
        'POST /',
        [PatientController::class, 'handleHospitalizationRegister']
      );

      Flight::route(
        'GET /@id:[0-9]+/alta',
        [PatientController::class, 'showEditHospitalization']
      );

      Flight::route(
        'POST /@id:[0-9]+',
        [PatientController::class, 'handleUpdateHospitalization']
      );
    });

    Flight::group('', function (): void {
      Flight::route(
        'POST /pacientes/@id',
        [PatientController::class, 'handleEdition']
      );
    }, [EnsureCanEditPatientMiddleware::class]);

    Flight::group('', function (): void {
      Flight::route('GET /usuarios', [UserController::class, 'showUsers']);

      Flight::route(
        'POST /usuarios',
        [UserController::class, 'handleRegister']
      );

      Flight::route(
        '/usuarios/@id/activar',
        [UserController::class, 'handleToggleStatus']
      );

      Flight::route(
        '/usuarios/@id/desactivar',
        [UserController::class, 'handleToggleStatus']
      );

      Flight::route(
        '/configuracion/permisos',
        [SettingsController::class, 'showPermissions']
      );

      Flight::route(
        'POST /configuracion/@id/permisos',
        [SettingsController::class, 'handlePermissionAssignment']
      );

      Flight::route(
        'GET /configuracion/respaldo-restauracion',
        [SettingsController::class, 'showBackups']
      );

      Flight::route(
        'POST /configuracion/respaldo-restauracion',
        [SettingsController::class, 'loadBackupFile']
      );

      Flight::route(
        '/configuracion/respaldar',
        [SettingsController::class, 'handleCreateBackup']
      );

      Flight::route(
        '/configuracion/restaurar',
        [SettingsController::class, 'handleRestoreBackup']
      );

      Flight::route('/logs', [SettingsController::class, 'showLogs']);
      Flight::route('/logs/vaciar', [SettingsController::class, 'cleanLogs']);

      Flight::route(
        'GET /configuracion/causas-de-consulta',
        [SettingsController::class, 'showConsultationCausesConfigs']
      )
        ->addMiddleware(new AuthorizationMiddleware(
          permitted: Appointment::Coordinator,
          blocked: null,
        ));

      Flight::route(
        'POST /configuracion/causas-de-consulta',
        [SettingsController::class, 'handleConsultationCausesUpdate']
      )
        ->addMiddleware(new AuthorizationMiddleware(
          permitted: Appointment::Coordinator,
          blocked: null,
        ));

      Flight::group('/', function (): void {
        Flight::route(
          'GET /departamentos',
          [DepartmentController::class, 'showDepartments']
        );

        Flight::route(
          'POST /departamentos/@id',
          [DepartmentController::class, 'handleDepartmentEdition']
        );

        Flight::route(
          '/departamentos/@id/activar',
          [DepartmentController::class, 'handleToggleStatus']
        );

        Flight::route(
          '/departamentos/@id/desactivar',
          [DepartmentController::class, 'handleToggleStatus']
        );

        Flight::route(
          'GET /configuracion/institucion',
          [SettingsController::class, 'showInstitutionConfigs']
        );

        Flight::route(
          'POST /configuracion/institucion',
          [SettingsController::class, 'handleInstitutionUpdate']
        );
      }, [new AuthorizationMiddleware(
        permitted: Appointment::Director,
        blocked: null,
      )]);
    }, [new AuthorizationMiddleware(
      permitted: Appointment::Coordinator,
      blocked: null,
    )]);
  }, [EnsureOneSelectedDepartment::class, EnsureDepartmentIsActive::class]);

  Flight::route(
    'GET /reportes/epi-11',
    [ReportsController::class, 'showEpi11']
  );

  Flight::route(
    'GET /reportes/epi-15',
    [ReportsController::class, 'showEpi15']
  );
}, [AuthenticationMiddleware::class]);
