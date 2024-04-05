<?php

use App\Controllers\Web\DepartmentWebController;
use App\Controllers\Web\HomeWebController;
use App\Controllers\Web\PatientWebController;
use App\Controllers\Web\SessionWebController;
use App\Controllers\Web\SettingsWebController;
use App\Controllers\Web\UserWebController;
use App\Middlewares\AuthenticationMiddleware;
use App\Middlewares\AuthorizationMiddleware;
use App\Middlewares\EnsureOneSelectedDepartment;
use App\Middlewares\EnsureOnlyAcceptOneDirector;
use App\Middlewares\EnsureUserIsNotAuthenticated;
use App\Middlewares\MessagesMiddleware;
use App\Middlewares\ShowRegisterIfThereIsNoUsers;
use App\ValueObjects\Appointment;

App::group('', function (): void {
  App::route('/salir', [SessionWebController::class, 'logOut']);

  App::group('', function (): void {
    App::route('GET /ingresar', [SessionWebController::class, 'showLogin']);
    App::route('POST /ingresar', [SessionWebController::class, 'handleLogin']);
    App::route('GET /recuperar', [UserWebController::class, 'showPasswordReset']);
    App::route('POST /recuperar', [UserWebController::class, 'handlePasswordReset']);
  }, [ShowRegisterIfThereIsNoUsers::class]);

  App::group('', function (): void {
    App::route('GET /registrate', [UserWebController::class, 'showRegister']);
    App::route('POST /registrate', [UserWebController::class, 'handleRegister']);
  }, [EnsureOnlyAcceptOneDirector::class]);
}, [EnsureUserIsNotAuthenticated::class, MessagesMiddleware::class]);

App::group('', function (): void {
  App::route('/departamento/seleccionar', [SessionWebController::class, 'showDepartments']);
  App::route('/departamento/seleccionar/@id', [SessionWebController::class, 'saveChoice']);

  App::group('', function (): void {
    App::route('/', [HomeWebController::class, 'showIndex']);
    App::route('GET /perfil', [UserWebController::class, 'showProfile']);
    App::route('POST /perfil', [UserWebController::class, 'handlePasswordChange']);
    App::route('GET /perfil/editar', [UserWebController::class, 'showEditProfile']);
    App::route('POST /perfil/editar', [UserWebController::class, 'handleEditProfile']);

    App::route('/notificaciones', function (): void {
    });

    App::group('', function (): void {
      App::route('GET /pacientes', [PatientWebController::class, 'showPatients']);
      App::route('POST /pacientes', [PatientWebController::class, 'handleRegister']);
      App::route('POST /pacientes/@id', [PatientWebController::class, 'handleEdition']);
      App::route('GET /pacientes/@id', [PatientWebController::class, 'showPatient']);
    }, [new AuthorizationMiddleware(Appointment::Secretary, Appointment::Director)]);

    App::group('', function (): void {
      App::route('GET /usuarios', [UserWebController::class, 'showUsers']);
      App::route('POST /usuarios', [UserWebController::class, 'handleRegister']);
      App::route('/usuarios/@id/activar', [UserWebController::class, 'handleToggleStatus']);
      App::route('/usuarios/@id/desactivar', [UserWebController::class, 'handleToggleStatus']);
      App::route('/configuracion/permisos', [SettingsWebController::class, 'showPermissions']);
      App::route('POST /configuracion/@id/permisos', [SettingsWebController::class, 'handlePermissionAssignment']);

      App::route('/configuracion/respaldo-restauracion', [SettingsWebController::class, 'showBackups']);
      App::route('/configuracion/respaldar', [SettingsWebController::class, 'handleCreateBackup']);
      App::route('/configuracion/restaurar', [SettingsWebController::class, 'handleRestoreBackup']);

      App::group('', function (): void {
        App::route('GET /departamentos', [DepartmentWebController::class, 'showDepartments']);
        App::route('POST /departamentos', [DepartmentWebController::class, 'handleRegister']);
        App::route('POST /departamentos/@id', [DepartmentWebController::class, 'handleDepartmentEdition']);
        App::route('/departamentos/@id/activar', [DepartmentWebController::class, 'handleToggleStatus']);
        App::route('/departamentos/@id/desactivar', [DepartmentWebController::class, 'handleToggleStatus']);
        App::route('GET /configuracion/institucion', [SettingsWebController::class, 'showInstitutionConfigs']);
        App::route('POST /configuracion/institucion', [SettingsWebController::class, 'handleInstitutionUpdate']);
      }, [new AuthorizationMiddleware(Appointment::Director)]);
    }, [new AuthorizationMiddleware(Appointment::Coordinator)]);
  }, [EnsureOneSelectedDepartment::class]);
}, [AuthenticationMiddleware::class, MessagesMiddleware::class]);
