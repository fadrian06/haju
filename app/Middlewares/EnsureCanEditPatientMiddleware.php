<?php



namespace HAJU\Middlewares;

use HAJU\Models\User;
use HAJU\Repositories\Domain\PatientRepository;
use Flight;
use Leaf\Http\Session;

final readonly class EnsureCanEditPatientMiddleware
{
  public function __construct(private PatientRepository $patientRepository)
  {
    // ...
  }

  public function before(array $params): ?true
  {
    $patient = $this->patientRepository->getById($params['id']);
    $loggedUser = Flight::view()->get('user');
    assert($loggedUser instanceof User);

    if ($patient->canBeEditedBy($loggedUser)) {
      return true;
    }

    Session::set('error', 'Acceso no autorizado');
    Flight::redirect('/');

    return null;
  }
}
