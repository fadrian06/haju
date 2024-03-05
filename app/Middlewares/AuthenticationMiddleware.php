<?php

namespace App\Middlewares;

use App;

class AuthenticationMiddleware {
  static function before(): void {
    if (
      !App::session()->get('userId')
      || !$user = App::userRepository()->getById((int) App::session()->get('userId'))
    ) {
      exit(App::redirect('/salir'));
    }

    App::view()->set('user', $user);
  }
}
