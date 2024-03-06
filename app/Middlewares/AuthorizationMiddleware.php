<?php

namespace App\Middlewares;

use App;
use App\Models\Role;
use App\Models\User;

class AuthorizationMiddleware {
  private static Role $permitted;

  function __construct(Role $permitted) {
    self::$permitted = $permitted;
  }

  static function before(): void {
    $user = App::view()->get('user');

    if (!$user instanceof User || $user->role !== self::$permitted) {
      exit(App::redirect('/'));
    }
  }
}
