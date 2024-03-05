<?php

namespace App\Models;

use DateTime;

class Department extends Model {
  function __construct(
    public readonly string $name,
    public readonly ?DateTime $registered = null
  ) {
  }
}
