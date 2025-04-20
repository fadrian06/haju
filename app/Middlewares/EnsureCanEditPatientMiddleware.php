<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\OldModels\User;
use App\Repositories\Domain\PatientRepository;
use Flight;
use flight\template\View;
use Leaf\Http\Session;

final readonly class EnsureCanEditPatientMiddleware {
  public function __construct(
    private PatientRepository $patientRepository,
    private View $view,
    private Session $session,
  ) {
  }

  public function before(array $params): ?true {
    $patient = $this->patientRepository->getById($params['id']);
    $loggedUser = $this->view->get('user');
    assert($loggedUser instanceof User);

    if ($patient->canBeEditedBy($loggedUser)) {
      return true;
    }

    $this->session->set('error', 'Acceso no autorizado');
    Flight::redirect('/');

    return null;
  }
}
