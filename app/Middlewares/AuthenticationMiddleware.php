<?php

namespace App\Middlewares;

use App;

class AuthenticationMiddleware {
  static function before(): void {
    if (
      !App::session()->get('userId')
      || !$user = App::userRepository()->getById((int) App::session()->get('userId'))
    ) {
      App::redirect('/salir');

      return;
    }

    App::view()->set('user', $user);
  }
}
