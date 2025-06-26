<?php

namespace HAJU\Middlewares;

use Flight;
use Leaf\Http\Session;

final readonly class EnsureUserIsNotAuthenticated
{
  public function before(): void
  {
    if (Session::has('userId')) {
      Flight::redirect('/');

      return;
    }
  }
}
