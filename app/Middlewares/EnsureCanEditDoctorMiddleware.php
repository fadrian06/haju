<?php



namespace HAJU\Middlewares;

use HAJU\Models\User;
use HAJU\Repositories\Domain\DoctorRepository;
use Flight;
use Leaf\Http\Session;

final readonly class EnsureCanEditDoctorMiddleware
{
  public function __construct(private DoctorRepository $doctorRepository)
  {
    // ...
  }

  public function before(array $params): ?true
  {
    $doctor = $this->doctorRepository->getByIdCard(intval($params['idCard']));
    $loggedUser = Flight::view()->get('user');
    assert($loggedUser instanceof User);

    if ($doctor->canBeEditedBy($loggedUser)) {
      return true;
    }

    Session::set('error', 'Acceso no autorizado');
    Flight::redirect('/');

    return null;
  }
}
