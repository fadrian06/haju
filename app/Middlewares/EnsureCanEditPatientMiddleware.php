<?php

declare(strict_types=1);

namespace App\Middlewares;

use App;
use App\Models\User;

final readonly class EnsureCanEditPatientMiddleware {
  public static function before(array $params): true {
    $patient = App::patientRepository()->getById($params['id']);
    $loggedUser = App::view()->get('user');
    assert($loggedUser instanceof User);

    if ($patient->canBeEditedBy($loggedUser)) {
      return true;
    }

    App::session()->set('error', 'Acceso no autorizado');
    App::redirect('/');

    exit;
  }
}
