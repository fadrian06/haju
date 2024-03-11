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
    $user = App::userRepository()->getByIdCard((int) App::request()->data['id_card']);

    try {
      if (!$user?->checkPassword(App::request()->data['password'])) {
        throw new Error('Cédula o contraseña incorrecta');
      }

      if (!$user->isActive) {
        throw new Error('Este usuario se encuentra desactivado');
      }

      App::session()->set('userId', $user->getId());
      App::redirect('/departamento/seleccionar');

      return;
    } catch (Error $error) {
      self::setError($error->getMessage());
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
      App::redirect("/departamento/seleccionar/{$departments[0]->getId()}");

      return;
    }

    App::renderPage('select-department', 'Ingresar (2/2)');
  }

  static function saveChoice(string $id): void {
    App::session()->set('departmentId', $id);
    App::redirect('/');
  }
}
