<?php

declare(strict_types=1);

namespace App\Middlewares;

use App;
use App\Models\User;
use App\ValueObjects\Appointment;

final readonly class AuthorizationMiddleware {
  public function __construct(
    private Appointment $permitted,
    private ?Appointment $blocked = null
  ) {
  }

  public function before(): void {
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
