<?php

declare(strict_types=1);

namespace HAJU\Errors;

use Error;

final class UserDoNotHaveActiveDepartmentsError extends Error {
  protected $message = 'El usuario no tiene departamentos activos asignados';
}
