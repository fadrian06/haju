<?php

namespace App\Middlewares;

use App;
use App\Models\User;
use App\ValueObjects\Appointment;

final readonly class AuthorizationMiddleware {
  function __construct(
    private Appointment $permitted,
    private ?Appointment $blocked = null
  ) {
  }

  function before(): void {
    /** @var ?User */
    $user = App::view()->get('user');

    if (
      !$user?->appointment->isHigherThan($this->permitted)
      || ($this->blocked && $user->appointment === $this->blocked)
    ) {
      App::session()->set('error', 'Acceso no autorizado');
      App::redirect('/');

      exit;
    }
  }
}
