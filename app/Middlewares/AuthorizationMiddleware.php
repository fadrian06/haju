<?php

namespace App\Middlewares;

use App;
use App\Models\Role;
use App\Models\User;

class AuthorizationMiddleware {
  function __construct(private readonly Role $permitted) {
  }

  function before(): void {
    /** @var ?User */
    $user = App::view()->get('user');

    if (!$user?->role->isHigherThan($this->permitted)) {
      exit(App::redirect('/'));
    }
  }
}
