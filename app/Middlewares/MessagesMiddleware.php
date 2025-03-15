<?php

declare(strict_types=1);

namespace App\Middlewares;

use App;
use Leaf\Http\Session;

final readonly class MessagesMiddleware {
  public static function before(): void {
    App::view()->set('error', App::session()->retrieve('error', null, true));
    App::view()->set('message', App::session()->retrieve('message', null, true));
    App::view()->set('scriptPath', App::session()->get('scriptPath', null, true));
    App::view()->set('mustChangePassword', Session::get('mustChangePassword', false));
  }
}
