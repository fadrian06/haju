<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\Repositories\Domain\UserRepository;
use Flight;

final readonly class ShowRegisterIfThereIsNoUsers {
  public function __construct(private UserRepository $userRepository) {
  }

  public function before(): void {
    $users = $this->userRepository->getAll();

    if (!$users) {
      Flight::redirect('/registrate');

      return;
    }
  }
}
