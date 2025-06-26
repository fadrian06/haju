<?php



namespace HAJU\Middlewares;

use HAJU\Models\User;
use HAJU\Enums\Appointment;
use Flight;
use Leaf\Http\Session;

final readonly class AuthorizationMiddleware
{
  public function __construct(
    private Appointment $permitted,
    private ?Appointment $blocked = null,
  ) {
    // ...
  }

  public function before(): void
  {
    $user = Flight::view()->get('user');
    assert($user instanceof User || is_null($user));

    if (!$user?->appointment->isHigherThan($this->permitted) || ($this->blocked && $user->appointment === $this->blocked)) {
      Session::set('error', 'Acceso no autorizado');
      Flight::redirect('/');
    }
  }
}
