<?php

namespace App\Middlewares;

use App;
use App\Models\Appointment;
use App\Models\User;

class AuthorizationMiddleware {
  function __construct(private readonly Appointment $permitted) {
  }

  function before(): void {
    /** @var ?User */
    $user = App::view()->get('user');

    if (!$user?->appointment->isHigherThan($this->permitted)) {
      exit(App::redirect('/'));
    }
  }
}
