<?php

use App\Controllers\Web\DepartmentWebController;
use App\Controllers\Web\HomeWebController;
use App\Controllers\Web\SessionWebController;
use App\Controllers\Web\UserWebController;
use App\Middlewares\AuthenticationMiddleware;
use App\Middlewares\AuthorizationMiddleware;
use App\Middlewares\EnsureOnlyAcceptOneDirector;
use App\Middlewares\MessagesMiddleware;
use App\Models\Role;

$showRegister = App::userRepository()->getAll() === [];
App::view()->set(compact('showRegister'));

App::group('', function (): void {
  App::route('/salir', [SessionWebController::class, 'logOut']);
  App::route('GET /ingresar', [SessionWebController::class, 'showLogin']);
  App::route('POST /ingresar', [SessionWebController::class, 'handleLogin']);
  App::route('GET /recuperar', [UserWebController::class, 'showPasswordReset']);
  App::route('POST /recuperar', [UserWebController::class, 'handlePasswordReset']);

  App::group('', function (): void {
    App::route('GET /registrate', [UserWebController::class, 'showRegister']);
    App::route('POST /registrate', [UserWebController::class, 'handleRegister']);
  }, [EnsureOnlyAcceptOneDirector::class]);
}, [MessagesMiddleware::class]);

App::group('', function (): void {
  App::route('/', [HomeWebController::class, 'index']);
  App::route('GET /perfil', [UserWebController::class, 'showProfile']);
  App::route('POST /perfil', [UserWebController::class, 'handlePasswordChange']);
  App::route('GET /perfil/editar', [UserWebController::class, 'showEditProfile']);
  App::route('POST /perfil/editar', [UserWebController::class, 'handleEditProfile']);

  App::route('/notificaciones', function (): void {
  });

  App::group('', function (): void {
    App::route('GET /usuarios', [UserWebController::class, 'showUsers']);
    App::route('POST /usuarios', [UserWebController::class, 'handleRegister']);
    App::route('/usuarios/@id/activar', [UserWebController::class, 'handleToggleStatus']);
    App::route('/usuarios/@id/desactivar', [UserWebController::class, 'handleToggleStatus']);

    App::group('', function (): void {
      App::route('GET /departamentos', [DepartmentWebController::class, 'showDepartments']);
      App::route('POST /departamentos', [DepartmentWebController::class, 'handleRegister']);
      App::route('POST /departamentos/@id', [DepartmentWebController::class, 'handleDepartmentEdition']);
      App::route('/departamentos/@id/activar', [DepartmentWebController::class, 'handleToggleStatus']);
      App::route('/departamentos/@id/desactivar', [DepartmentWebController::class, 'handleToggleStatus']);

      App::route('/configuracion', function (): void {
      });
    }, [new AuthorizationMiddleware(Role::Director)]);
  }, [new AuthorizationMiddleware(Role::Coordinator)]);
}, [AuthenticationMiddleware::class, MessagesMiddleware::class]);
