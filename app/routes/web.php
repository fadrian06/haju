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
use flight\Container;
use flight\template\View;
use Leaf\Http\Session;

Flight::route('/', static function () {
  if (Container::getInstance()->get(Session::class)->has('userId')) {
    return true;
  }

  Container::getInstance()->get(LandingWebController::class)->showLanding();
});

Flight::group('', static function (): void {
  Flight::route('/salir', [SessionWebController::class, 'logOut']);

  Flight::group('', static function (): void {
    Flight::route('GET /ingresar', [SessionWebController::class, 'showLogin']);

    Flight::route(
      'POST /ingresar',
      [SessionWebController::class, 'handleLogin']
    );

    Flight::route(
      'GET /recuperar',
      [UserWebController::class, 'showPasswordReset']
    );

    Flight::route(
      'POST /recuperar',
      [UserWebController::class, 'handlePasswordReset']
    );
  });

  Flight::group('', static function (): void {
    Flight::route(
      'GET /registrate',
      [UserWebController::class, 'showRegister']
    );

    Flight::route(
      'POST /registrate',
      [UserWebController::class, 'handleRegister']
    );
  }, [EnsureOnlyAcceptOneDirector::class]);
}, [EnsureUserIsNotAuthenticated::class, MessagesMiddleware::class]);

Flight::group('', function (): void {
  Flight::route(
    'GET /hospitalizaciones',
    [PatientWebController::class, 'showHospitalizations']
  );

  Flight::route(
    'GET /consultas',
    [PatientWebController::class, 'showConsultations']
  );

  Flight::route(
    '/departamento/seleccionar',
    [SessionWebController::class, 'showDepartments']
  );

  Flight::route(
    '/departamento/seleccionar/@id',
    [SessionWebController::class, 'saveChoice']
  )
    ->addMiddleware(LogLoginMiddleware::class);

  Flight::group(
    '',
    static function (): void {
      Flight::route('/', [HomeWebController::class, 'showIndex']);
      Flight::route('GET /perfil', [UserWebController::class, 'showProfile']);

      Flight::route(
        'POST /perfil',
        [UserWebController::class, 'handlePasswordChange']
      );

      Flight::route(
        'GET /perfil/editar',
        [UserWebController::class, 'showEditProfile']
      );

      Flight::route(
        'POST /perfil/editar',
        [UserWebController::class, 'handleEditProfile']
      );

      Flight::group(
        '/doctores',
        static function (): void {
          Flight::route('GET /', [DoctorWebController::class, 'showDoctors']);
          Flight::route('POST /', [DoctorWebController::class, 'handleRegister']);

          Flight::group('/@idCard', function (): void {
            Flight::route('GET /', [DoctorWebController::class, 'showEdit']);
            Flight::route(
              'POST /',
              [DoctorWebController::class, 'handleEdition']
            );
          }, [EnsureCanEditDoctorMiddleware::class]);
        },
        [
          new AuthorizationMiddleware(
            permitted: Appointment::Coordinator,
            blocked: null,
            view: Container::getInstance()->get(View::class),
            session: Container::getInstance()->get(Session::class),
          )
        ]
      );

      Flight::route(
        'GET /pacientes',
        [PatientWebController::class, 'showPatients']
      );

      Flight::route(
        'GET /pacientes/@id:[0-9]+',
        [PatientWebController::class, 'showPatient']
      );

      Flight::route(
        'GET /pacientes/@id:[0-9]+/eliminar',
        [PatientWebController::class, 'deletePatient']
      );

      Flight::route(
        'POST /pacientes',
        [PatientWebController::class, 'handleRegister']
      );

      Flight::group('/consultas', function (): void {
        Flight::route(
          'GET /registrar',
          [PatientWebController::class, 'showConsultationRegister']
        );

        Flight::route(
          'POST /',
          [PatientWebController::class, 'handleConsultationRegister']
        );
      }, [EnsureSelectedDepartmentIsNotStatistics::class]);

      Flight::group('/hospitalizaciones', function (): void {
        Flight::route(
          'GET /registrar',
          [PatientWebController::class, 'showHospitalizationRegister']
        );

        Flight::route(
          'POST /',
          [PatientWebController::class, 'handleHospitalizationRegister']
        );

        Flight::route(
          'GET /@id:[0-9]+/alta',
          [PatientWebController::class, 'showEditHospitalization']
        );

        Flight::route(
          'POST /@id:[0-9]+',
          [PatientWebController::class, 'handleUpdateHospitalization']
        );
      });

      Flight::group('', function (): void {
        Flight::route(
          'POST /pacientes/@id',
          [PatientWebController::class, 'handleEdition']
        );
      }, [EnsureCanEditPatientMiddleware::class]);

      Flight::group('', function (): void {
        Flight::route('GET /usuarios', [UserWebController::class, 'showUsers']);

        Flight::route(
          'POST /usuarios',
          [UserWebController::class, 'handleRegister']
        );

        Flight::route(
          '/usuarios/@id/activar',
          [UserWebController::class, 'handleToggleStatus']
        );

        Flight::route(
          '/usuarios/@id/desactivar',
          [UserWebController::class, 'handleToggleStatus']
        );

        Flight::route(
          '/configuracion/permisos',
          [SettingsWebController::class, 'showPermissions']
        );

        Flight::route(
          'POST /configuracion/@id/permisos',
          [SettingsWebController::class, 'handlePermissionAssignment']
        );

        Flight::route(
          'GET /configuracion/respaldo-restauracion',
          [SettingsWebController::class, 'showBackups']
        );

        Flight::route(
          'POST /configuracion/respaldo-restauracion',
          [SettingsWebController::class, 'loadBackupFile']
        );

        Flight::route(
          '/configuracion/respaldar',
          [SettingsWebController::class, 'handleCreateBackup']
        );

        Flight::route(
          '/configuracion/restaurar',
          [SettingsWebController::class, 'handleRestoreBackup']
        );

        Flight::route('/logs', [SettingsWebController::class, 'showLogs']);

        Flight::route(
          '/logs/vaciar',
          [SettingsWebController::class, 'cleanLogs']
        );

        Flight::route(
          'GET /configuracion/causas-de-consulta',
          [SettingsWebController::class, 'showConsultationCausesConfigs']
        )
          ->addMiddleware(new AuthorizationMiddleware(
            permitted: Appointment::Coordinator,
            session: Container::getInstance()->get(Session::class),
            view: Container::getInstance()->get(View::class),
            blocked: null,
          ));

        Flight::route(
          'POST /configuracion/causas-de-consulta',
          [SettingsWebController::class, 'handleConsultationCausesUpdate']
        )
          ->addMiddleware(new AuthorizationMiddleware(
            permitted: Appointment::Coordinator,
            session: Container::getInstance()->get(Session::class),
            view: Container::getInstance()->get(View::class),
            blocked: null,
          ));

        Flight::group(
          '/',
          static function (): void {
            Flight::route(
              'GET /departamentos',
              [DepartmentWebController::class, 'showDepartments']
            );

            Flight::route(
              'POST /departamentos/@id',
              [DepartmentWebController::class, 'handleDepartmentEdition']
            );

            Flight::route(
              '/departamentos/@id/activar',
              [DepartmentWebController::class, 'handleToggleStatus']
            );

            Flight::route(
              '/departamentos/@id/desactivar',
              [DepartmentWebController::class, 'handleToggleStatus']
            );

            Flight::route(
              'GET /configuracion/institucion',
              [SettingsWebController::class, 'showInstitutionConfigs']
            );

            Flight::route(
              'POST /configuracion/institucion',
              [SettingsWebController::class, 'handleInstitutionUpdate']
            );
          },
          [
            new AuthorizationMiddleware(
              permitted: Appointment::Director,
              blocked: null,
              view: Container::getInstance()->get(View::class),
              session: Container::getInstance()->get(Session::class)
            )
          ]
        );
      }, [
        new AuthorizationMiddleware(
          permitted: Appointment::Coordinator,
          blocked: null,
          view: Container::getInstance()->get(View::class),
          session: Container::getInstance()->get(Session::class)
        )
      ]);
    },
    [EnsureOneSelectedDepartment::class, EnsureDepartmentIsActive::class]
  );

  Flight::route(
    'GET /reportes/epi-11',
    [ReportsWebController::class, 'showEpi11']
  );

  Flight::route(
    'GET /reportes/epi-15',
    [ReportsWebController::class, 'showEpi15']
  );
}, [AuthenticationMiddleware::class, MessagesMiddleware::class]);
