<?php

namespace App\Middlewares;

use App;
use App\Models\User;
use App\ValueObjects\Appointment;

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
