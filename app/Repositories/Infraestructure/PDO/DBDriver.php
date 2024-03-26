<?php

namespace App\Repositories\Infraestructure\PDO;

enum DBDriver: string {
  case MySQL = 'mysql';
  case SQLite = 'sqlite';
}
