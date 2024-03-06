<?php

namespace App\Controllers\Web;

use App;

class SessionWebController {
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

    if (!$user?->checkPassword(App::request()->data['password'])) {
      App::session()->set('error', '❌ Cédula o contraseña incorrecta');
      App::redirect('/ingresar');

      return;
    }

    App::session()->set('userId', $user->getId());
    App::redirect('/');
  }
}
