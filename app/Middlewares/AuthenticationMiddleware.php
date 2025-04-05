<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Repositories\Domain\UserRepository;
use Flight;
use flight\template\View;
use Leaf\Http\Session;

final readonly class AuthenticationMiddleware {
  public function __construct(
    private Session $session,
    private UserRepository $userRepository,
    private View $view,
  ) {
  }

  public function before(): void {
    if (
      !$this->session->get('userId')
      || !$user = $this->userRepository->getById((int) $this->session->get('userId'))
    ) {
      Flight::redirect('/salir');

      return;
    }

    $this->view->set('user', $user);
  }
}
