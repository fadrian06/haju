<?php

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
