<?php



namespace HAJU\Middlewares;

use HAJU\Repositories\Domain\UserRepository;
use Flight;

final readonly class ShowRegisterIfThereIsNoUsers
{
  public function __construct(private UserRepository $userRepository)
  {
  }

  public function before(): void
  {
    $users = $this->userRepository->getAll();

    if (!$users) {
      Flight::redirect('/registrate');

      return;
    }
  }
}
