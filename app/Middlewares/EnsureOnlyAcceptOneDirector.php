<?php

namespace App\Middlewares;

use App;
use App\Models\User;
use App\ValueObjects\Appointment;

class EnsureOnlyAcceptOneDirector {
  static function before(): void {
    $users = App::userRepository()->getAll();

    $directors = array_filter($users, function (User $user): bool {
      return $user->appointment === Appointment::Director;
    });

    $activeDirectors = array_filter($directors, function (User $director): bool {
      return $director->isActive();
    });

    if (!$directors || !$activeDirectors) {
      return;
    }

    exit(App::redirect('/ingresar'));
  }
}
