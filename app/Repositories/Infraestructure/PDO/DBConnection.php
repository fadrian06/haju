<?php

namespace App\Repositories\Infraestructure\PDO;

enum DBConnection: string {
  case MySQL = 'mysql';
  case SQLite = 'sqlite';
}
