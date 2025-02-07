<?php

namespace App\Middlewares;

use App;
use App\Models\User;

final readonly class EnsureCanEditPatientMiddleware {
  static function before(array $params) {
    $patient = App::patientRepository()->getById($params['id']);
    $loggedUser = App::view()->get('user');
    assert($loggedUser instanceof User);

    if ($patient->canBeEditedBy($loggedUser)) {
      return true;
    }

    App::session()->set('error', 'Acceso no autorizado');
    exit(App::redirect('/'));
  }
}
