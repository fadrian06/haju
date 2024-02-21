<?php

namespace App\Repositories\Infraestructure\PDO;

use PDO;

class Connection {
  private readonly PDO $instance;

  /** @param string|'memory' $dbName */
  function __construct(
    DBConnection $dbConnection,
    string $dbName,
    ?string $host = null,
    ?int $port = null,
    ?string $user = null,
    ?string $password = null
  ) {
    $this->instance = new PDO(
      $dbConnection === DBConnection::MySQL
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
