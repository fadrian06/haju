<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Models\User;
use App\ValueObjects\Appointment;
use Flight;
use flight\template\View;
use Leaf\Http\Session;

final readonly class AuthorizationMiddleware {
  public function __construct(
    private View $view,
    private Session $session,
    private Appointment $permitted,
    private ?Appointment $blocked = null,
  ) {
  }

  public function before(): void {
    $user = $this->view->get('user');
    assert($user instanceof User || is_null($user));

    if (
      !$user?->appointment->isHigherThan($this->permitted)
      || ($this->blocked && $user->appointment === $this->blocked)
    ) {
      $this->session->set('error', 'Acceso no autorizado');
      Flight::redirect('/');
    }
  }
}
