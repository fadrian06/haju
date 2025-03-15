<?php

declare(strict_types=1);

namespace App\Middlewares;

use App;
use App\Models\User;

final readonly class EnsureCanEditDoctorMiddleware {
  public static function before(array $params): true {
    $doctor = App::doctorRepository()->getByIdCard($params['idCard']);
    $loggedUser = App::view()->get('user');
    assert($loggedUser instanceof User);

    if ($doctor->canBeEditedBy($loggedUser)) {
      return true;
    }

    App::session()->set('error', 'Acceso no autorizado');
    App::redirect('/');

    exit;
  }
}
