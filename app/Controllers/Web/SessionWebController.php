<?php

namespace App\Controllers\Web;

use App;
use App\Models\User;
use Error;

class SessionWebController extends Controller {
  static function logOut(): void {
    App::session()->destroy();
    App::redirect('/ingresar');
  }

  static function showLogin(): void {
    App::renderPage('login', 'Ingreso (1/2)');
  }

  static function handleLogin(): void {
    $data = App::request()->data;
    $user = App::userRepository()->getByIdCard((int) $data['id_card']);

    try {
      if (!$data['id_card']) {
        throw new Error('La cédula es requerida');
      }

      if (!$data['password']) {
        throw new Error('La contraseña es requerida');
      }

      if (!$user?->checkPassword(App::request()->data['password'])) {
        throw new Error('Cédula o contraseña incorrecta');
      }

      $user->ensureThatIsActive()->ensureHasActiveDepartments();

      App::session()->set('userId', $user->getId());

      exit(App::redirect('/departamento/seleccionar'));
    } catch (Error $error) {
      self::setError($error);
    }

    App::redirect('/ingresar');
  }

  static function showDepartments(): void {
    $loggedUser = App::view()->get('user');
    $departments = [];

    assert($loggedUser instanceof User);

    foreach ($loggedUser->getDepartment() as $department) {
      $departments[] = $department;
    }

    App::session()->set('canChangeDepartment', count($departments) !== 1);

    if (count($departments) === 1) {
      exit(App::redirect("/departamento/seleccionar/{$departments[0]->getId()}"));
    }

    App::renderPage('select-department', 'Ingresar (2/2)');
  }

  static function saveChoice(string $id): void {
    App::session()->set('departmentId', $id);
    App::redirect('/');
  }
}
