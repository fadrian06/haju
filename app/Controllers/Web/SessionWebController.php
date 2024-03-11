<?php

namespace App\Controllers\Web;

use App;
use Error;

class SessionWebController extends Controller {
  static function logOut(): void {
    App::session()->destroy();
    App::redirect('/ingresar');
  }

  static function showLogin(): void {
    if (App::session()->get('userId')) {
      App::redirect('/');

      return;
    }

    App::render('pages/login', [], 'content');
    App::render('layouts/base', ['title' => 'Ingreso']);
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
    App::renderPage('select-department', 'Ingresar (2/2)');
  }

  static function saveChoice(string $id): void {
    App::session()->set('departmentId', $id);
    App::redirect('/');
  }
}
