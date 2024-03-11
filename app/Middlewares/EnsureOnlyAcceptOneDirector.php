<?php

namespace App\Middlewares;

use App;
use App\Models\Role;
use App\Models\User;

class EnsureOnlyAcceptOneDirector {
  static function before(): void {
    $users = App::userRepository()->getAll();

    $directors = array_filter($users, function (User $user): bool {
      return $user->role === Role::Director;
    });

    if ($directors !== []) {
      exit(App::redirect('/ingresar'));
    }
  }
}
