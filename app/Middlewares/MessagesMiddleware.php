<?php

namespace App\Middlewares;

use App;
use Leaf\Http\Session;

class MessagesMiddleware {
  static function before(): void {
    App::view()->set('error', App::session()->retrieve('error', null, true));
    App::view()->set('message', App::session()->retrieve('message', null, true));
    App::view()->set('mustChangePassword', Session::get('mustChangePassword', false));
  }
}
