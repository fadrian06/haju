<?php

declare(strict_types=1);

namespace App\Middlewares;

use App;

final readonly class EnsureUserIsNotAuthenticated {
  public static function before(): void {
    if (App::session()->get('userId')) {
      App::redirect('/');

      return;
    }
  }
}
