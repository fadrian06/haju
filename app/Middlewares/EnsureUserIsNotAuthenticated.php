<?php

declare(strict_types=1);

namespace App\Middlewares;

use Flight;
use Leaf\Http\Session;

final readonly class EnsureUserIsNotAuthenticated {
  public function __construct(private Session $session) {
    // ...
  }

  public function before() {
    if ($this->session->has('userId')) {
      Flight::redirect('/');

      return;
    }
  }
}
