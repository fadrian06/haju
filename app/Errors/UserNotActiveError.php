<?php

declare(strict_types=1);

namespace HAJU\Errors;

use Error;

final class UserNotActiveError extends Error {
  protected $message = 'Tu cuenta ha sido desactivada';
}
