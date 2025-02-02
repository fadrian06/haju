<?php

namespace App\Middlewares;

use App;
use App\Models\User;

final readonly class EnsureCanEditDoctorMiddleware {
  static function before($params) {
    $doctor = App::doctorRepository()->getByIdCard($params['idCard']);
    $loggedUser = App::view()->get('user');
    assert($loggedUser instanceof User);

    if ($doctor->canBeEditedBy($loggedUser)) {
      return true;
    }

    App::session()->set('error', 'Acceso no autorizado');
    exit(App::redirect('/'));
  }
}
