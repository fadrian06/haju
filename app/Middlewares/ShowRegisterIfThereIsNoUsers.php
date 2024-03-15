<?php

namespace App\Middlewares;

use App;

class ShowRegisterIfThereIsNoUsers {
  static function before(): void {
    $users = App::userRepository()->getAll();

    if (!$users) {
      App::redirect('/registrate');

      return;
    }
  }
}
