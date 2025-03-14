<?php

declare(strict_types=1);

namespace App\Middlewares;

use App;

class EnsureUserIsNotAuthenticated {
  static function before(): void {
    if (App::session()->get('userId')) {
      App::redirect('/');

      return;
    }
  }
}
