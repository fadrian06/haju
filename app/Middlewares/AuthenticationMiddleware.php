<?php

declare(strict_types=1);

namespace HAJU\Middlewares;

use HAJU\Models\User;
use Flight;

final readonly class AuthenticationMiddleware
{
  public function before(): void
  {
    $user = Flight::view()->get('user');
    assert($user instanceof User || is_null($user));

    if ($user === null) {
      Flight::redirect('/salir');

      return;
    }
  }
}
