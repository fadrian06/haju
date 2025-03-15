<?php

declare(strict_types=1);

namespace App\Middlewares;

use App;

final readonly class ShowRegisterIfThereIsNoUsers {
  public static function before(): void {
    $users = App::userRepository()->getAll();

    if (!$users) {
      App::redirect('/registrate');

      return;
    }
  }
}
