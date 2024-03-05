<?php

use App\Controllers\Web\HomeWebController;
use App\Controllers\Web\SessionWebController;
use App\Controllers\Web\UserWebController;
use App\Middlewares\AuthenticationMiddleware;
use App\Middlewares\AuthorizationMiddleware;
use App\Middlewares\MessagesMiddleware;
use App\Models\Role;

$showRegister = App::userRepository()->getAll() === [];
App::view()->set(compact('showRegister'));

App::group('', function (): void {
  App::route('/salir', [SessionWebController::class, 'logOut']);
  App::route('GET /ingresar', [SessionWebController::class, 'showLogin']);
  App::route('POST /ingresar', [SessionWebController::class, 'handleLogin']);

  App::route('GET /registrate', [UserWebController::class, 'showRegister']);
  App::route('POST /registrate', [UserWebController::class, 'handleRegister']);
  App::route('GET /recuperar', [UserWebController::class, 'showPasswordReset']);
  App::route('POST /recuperar', [UserWebController::class, 'handlePasswordReset']);
}, [MessagesMiddleware::class]);

App::group('', function (): void {
  App::route('/', [HomeWebController::class, 'index']);
  App::route('/perfil', [UserWebController::class, 'showProfile']);
  App::route('GET /perfil/editar', [UserWebController::class, 'showEditProfile']);
  App::route('POST /perfil/editar', [UserWebController::class, 'handleEditProfile']);

  App::route('/notificaciones', function (): void {
  });

  App::group('', function (): void {
    App::route('/usuarios', [UserWebController::class, 'showUsers']);

    App::route('/departamentos', function (): void {

    });

    App::route('/configuracion', function (): void {
    });
  }, [new AuthorizationMiddleware(Role::Director)]);
}, [AuthenticationMiddleware::class, MessagesMiddleware::class]);
