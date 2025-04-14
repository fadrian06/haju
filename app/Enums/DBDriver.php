<?php

declare(strict_types=1);

namespace App\Enums;

enum DBDriver: string {
  case MySQL = 'mysql';
  case SQLite = 'sqlite';
}
