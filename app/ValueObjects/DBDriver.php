<?php

declare(strict_types=1);

namespace App\ValueObjects;

enum DBDriver: string {
  case MySQL = 'mysql';
  case SQLite = 'sqlite';
}
