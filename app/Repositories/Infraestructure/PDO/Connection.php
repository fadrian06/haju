<?php

namespace App\Repositories\Infraestructure\PDO;

use App\ValueObjects\DBDriver;
use PDO;

final readonly class Connection {
  private PDO $instance;

  /** @param string|'memory' $dbName */
  function __construct(
    public DBDriver $driver,
    public string $dbName,
    ?string $host = null,
    ?int $port = null,
    ?string $user = null,
    ?string $password = null
  ) {
    $this->instance = new PDO(
      $driver === DBDriver::MySQL
        ? "mysql:host=$host; dbname=$dbName; charset=utf8; port=$port"
        : "sqlite:$dbName",
      $user,
      $password
    );
  }

  function instance(): PDO {
    return $this->instance;
  }
}
