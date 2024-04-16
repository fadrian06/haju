<?php

namespace App\Middlewares;

use App;
use App\Models\User;
use App\ValueObjects\Appointment;

final class AuthorizationMiddleware {
  private readonly ?Appointment $blocked;

  function __construct(
    private readonly Appointment $permitted,
    ?Appointment $blocked = null
  ) {
    $this->blocked = $blocked;
  }

  function before(): void {
    /** @var ?User */
    $user = App::view()->get('user');

    if (
      !$user?->appointment->isHigherThan($this->permitted)
      || ($this->blocked && $user->appointment === $this->blocked)
    ) {
      App::session()->set('error', 'Acceso no autorizado');
      exit(App::redirect('/'));
    }
  }
}
