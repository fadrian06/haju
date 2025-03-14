<?php

declare(strict_types=1);

namespace App\Middlewares;

use App;

class AuthenticationMiddleware {
  static function before(): void {
    if (
      !App::session()->get('userId')
      || !$user = App::userRepository()->getById((int) App::session()->get('userId'))
    ) {
      App::redirect('/salir');

      exit;
    }

    App::view()->set('user', $user);
  }
}
