<?php

namespace App\Repositories\Infraestructure\PDO;

use PDO;

class Connection {
  private readonly PDO $instance;

  /** @param string|'memory' $dbName */
  function __construct(
    public readonly DBConnection $driver,
    public readonly string $dbName,
    ?string $host = null,
    ?int $port = null,
    readonly ?string $user = null,
    ?string $password = null
  ) {
    $this->instance = new PDO(
      $driver === DBConnection::MySQL
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
