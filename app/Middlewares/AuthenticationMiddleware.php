<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\OldModels\User;
use Flight;
use flight\template\View;

final readonly class AuthenticationMiddleware {
  public function __construct(private View $view) {
  }

  public function before(): void {
    $user = $this->view->get('user');
    assert($user instanceof User || is_null($user));

    if ($user === null) {
      Flight::redirect('/salir');

      return;
    }
  }
}
