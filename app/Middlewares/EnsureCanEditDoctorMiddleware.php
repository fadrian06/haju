<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Models\User;
use App\Repositories\Domain\DoctorRepository;
use Flight;
use flight\template\View;
use Leaf\Http\Session;

final readonly class EnsureCanEditDoctorMiddleware {
  public function __construct(
    private DoctorRepository $doctorRepository,
    private View $view,
    private Session $session,
  ) {
  }

  public function before(array $params): ?true {
    $doctor = $this->doctorRepository->getByIdCard(intval($params['idCard']));
    $loggedUser = $this->view->get('user');
    assert($loggedUser instanceof User);

    if ($doctor->canBeEditedBy($loggedUser)) {
      return true;
    }

    $this->session->set('error', 'Acceso no autorizado');
    Flight::redirect('/');

    return null;
  }
}
