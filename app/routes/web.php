<?php

declare(strict_types=1);

use App\Controllers\Web\DepartmentWebController;
use App\Controllers\Web\DoctorWebController;
use App\Controllers\Web\HomeWebController;
use App\Controllers\Web\LandingWebController;
use App\Controllers\Web\PatientWebController;
use App\Controllers\Web\ReportsWebController;
use App\Controllers\Web\SessionWebController;
use App\Controllers\Web\SettingsWebController;
use App\Controllers\Web\UserWebController;
use App\Middlewares\AuthenticationMiddleware;
use App\Middlewares\AuthorizationMiddleware;
use App\Middlewares\EnsureCanEditDoctorMiddleware;
use App\Middlewares\EnsureCanEditPatientMiddleware;
use App\Middlewares\EnsureDepartmentIsActive;
use App\Middlewares\EnsureOneSelectedDepartment;
use App\Middlewares\EnsureOnlyAcceptOneDirector;
use App\Middlewares\EnsureSelectedDepartmentIsNotStatistics;
use App\Middlewares\EnsureUserIsNotAuthenticated;
use App\Middlewares\LogLoginMiddleware;
use App\Middlewares\MessagesMiddleware;
use App\ValueObjects\Appointment;
use Illuminate\Container\Container;
use Leaf\Http\Session;

App::route('/', static function () {
  if (Session::has('userId')) {
    return true;
  }

  Container::getInstance()->get(LandingWebController::class)->showLanding();
});

App::group('', static function (): void {
  App::route('/salir', [SessionWebController::class, 'logOut']);

  App::group('', static function (): void {
    App::route('GET /ingresar', [SessionWebController::class, 'showLogin']);
    App::route('POST /ingresar', [SessionWebController::class, 'handleLogin']);

    App::route(
      'GET /recuperar',
      [UserWebController::class, 'showPasswordReset']
    );

    App::route(
      'POST /recuperar',
      [UserWebController::class, 'handlePasswordReset']
    );
  });

  App::group('', static function (): void {
    App::route('GET /registrate', [UserWebController::class, 'showRegister']);

    App::route(
      'POST /registrate',
      [UserWebController::class, 'handleRegister']
    );
  }, [EnsureOnlyAcceptOneDirector::class]);
}, [EnsureUserIsNotAuthenticated::class, MessagesMiddleware::class]);

App::group('', function (): void {
  App::route(
    '/departamento/seleccionar',
    [SessionWebController::class, 'showDepartments']
  );

  App::route(
    '/departamento/seleccionar/@id',
    [SessionWebController::class, 'saveChoice']
  )
    ->addMiddleware(LogLoginMiddleware::class);

  App::group('', function (): void {
    App::route('/', [HomeWebController::class, 'showIndex']);
    App::route('GET /perfil', [UserWebController::class, 'showProfile']);

    App::route(
      'POST /perfil',
      [UserWebController::class, 'handlePasswordChange']
    );

    App::route(
      'GET /perfil/editar',
      [UserWebController::class, 'showEditProfile']
    );

    App::route(
      'POST /perfil/editar',
      [UserWebController::class, 'handleEditProfile']
    );

    App::group('/doctores', function (): void {
      App::route('GET /', [DoctorWebController::class, 'showDoctors']);
      App::route('POST /', [DoctorWebController::class, 'handleRegister']);

      App::group('/@idCard', function (): void {
        App::route('GET /', [DoctorWebController::class, 'showEdit']);
        App::route('POST /', [DoctorWebController::class, 'handleEdition']);
      }, [EnsureCanEditDoctorMiddleware::class]);
    }, [new AuthorizationMiddleware(Appointment::Coordinator)]);

    App::route('GET /pacientes', [PatientWebController::class, 'showPatients']);

    App::route(
      'GET /pacientes/@id:[0-9]+',
      [PatientWebController::class, 'showPatient']
    );

    App::route(
      'GET /pacientes/@id:[0-9]+/eliminar',
      [PatientWebController::class, 'deletePatient']
    );

    App::route(
      'POST /pacientes',
      [PatientWebController::class, 'handleRegister']
    );

    App::group('/consultas', function (): void {
      App::route(
        'GET /registrar',
        [PatientWebController::class, 'showConsultationRegister']
      );

      App::route(
        'POST /',
        [PatientWebController::class, 'handleConsultationRegister']
      );
    }, [EnsureSelectedDepartmentIsNotStatistics::class]);

    App::group('/hospitalizaciones', function (): void {
      App::route(
        'GET /registrar',
        [PatientWebController::class, 'showHospitalizationRegister']
      );

      App::route(
        'POST /',
        [PatientWebController::class, 'handleHospitalizationRegister']
      );

      App::route(
        'GET /@id:[0-9]+/alta',
        [PatientWebController::class, 'showEditHospitalization']
      );

      App::route(
        'POST /@id:[0-9]+',
        [PatientWebController::class, 'handleUpdateHospitalization']
      );
    });

    App::group('', function (): void {
      App::route(
        'POST /pacientes/@id',
        [PatientWebController::class, 'handleEdition']
      );
    }, [EnsureCanEditPatientMiddleware::class]);

    App::group('', function (): void {
      App::route('GET /usuarios', [UserWebController::class, 'showUsers']);

      App::route(
        'POST /usuarios',
        [UserWebController::class, 'handleRegister']
      );

      App::route(
        '/usuarios/@id/activar',
        [UserWebController::class, 'handleToggleStatus']
      );

      App::route(
        '/usuarios/@id/desactivar',
        [UserWebController::class, 'handleToggleStatus']
      );

      App::route(
        '/configuracion/permisos',
        [SettingsWebController::class, 'showPermissions']
      );

      App::route(
        'POST /configuracion/@id/permisos',
        [SettingsWebController::class, 'handlePermissionAssignment']
      );

      App::route(
        'GET /configuracion/respaldo-restauracion',
        [SettingsWebController::class, 'showBackups']
      );

      App::route(
        'POST /configuracion/respaldo-restauracion',
        [SettingsWebController::class, 'loadBackupFile']
      );

      App::route(
        '/configuracion/respaldar',
        [SettingsWebController::class, 'handleCreateBackup']
      );

      App::route(
        '/configuracion/restaurar',
        [SettingsWebController::class, 'handleRestoreBackup']
      );

      App::route('/logs', [SettingsWebController::class, 'showLogs']);
      App::route('/logs/vaciar', [SettingsWebController::class, 'cleanLogs']);

      App::route(
        'GET /configuracion/causas-de-consulta',
        [SettingsWebController::class, 'showConsultationCausesConfigs']
      )
        ->addMiddleware(new AuthorizationMiddleware(Appointment::Coordinator));

      App::route(
        'POST /configuracion/causas-de-consulta',
        [SettingsWebController::class, 'handleConsultationCausesUpdate']
      )
        ->addMiddleware(new AuthorizationMiddleware(Appointment::Coordinator));

      App::group('/', function (): void {
        App::route(
          'GET /departamentos',
          [DepartmentWebController::class, 'showDepartments']
        );

        App::route(
          'POST /departamentos/@id',
          [DepartmentWebController::class, 'handleDepartmentEdition']
        );

        App::route(
          '/departamentos/@id/activar',
          [DepartmentWebController::class, 'handleToggleStatus']
        );

        App::route(
          '/departamentos/@id/desactivar',
          [DepartmentWebController::class, 'handleToggleStatus']
        );

        App::route(
          'GET /configuracion/institucion',
          [SettingsWebController::class, 'showInstitutionConfigs']
        );

        App::route(
          'POST /configuracion/institucion',
          [SettingsWebController::class, 'handleInstitutionUpdate']
        );
      }, [new AuthorizationMiddleware(Appointment::Director)]);
    }, [new AuthorizationMiddleware(Appointment::Coordinator)]);
  }, [EnsureOneSelectedDepartment::class, EnsureDepartmentIsActive::class]);

  App::route(
    'GET /reportes/epi-11',
    [ReportsWebController::class, 'showEpi11']
  );

  App::route(
    'GET /reportes/epi-15',
    [ReportsWebController::class, 'showEpi15']
  );
}, [AuthenticationMiddleware::class, MessagesMiddleware::class]);
