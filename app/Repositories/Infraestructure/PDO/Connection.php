<?php

namespace App\Repositories\Infraestructure\PDO;

use App\ValueObjects\DBDriver;
use PDO;

final class Connection {
  private readonly PDO $instance;

  /** @param string|'memory' $dbName */
  function __construct(
    public readonly DBDriver $driver,
    public readonly string $dbName,
    ?string $host = null,
    ?int $port = null,
    readonly ?string $user = null,
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
