<?php

namespace App\ValueObjects;

enum DBDriver: string {
  case MySQL = 'mysql';
  case SQLite = 'sqlite';
}
