<?php

namespace App\Repositories\Infraestructure\PDO;

use App\Repositories\Domain\SettingsRepository;

class PDOSettingsRepository extends PDORepository implements SettingsRepository {
  function backupExists(): bool {
    switch ($this->connection->driver) {
      case DBConnection::SQLite:
        return file_exists(str_replace('.db', '.backup.db', $this->connection->dbName));

      case DBConnection::MySQL:
        return file_exists(__DIR__ . '/../../../database/backup.mysql.sql');
    }
  }

  function backup(): void {
    switch ($this->connection->driver) {
      case DBConnection::SQLite:
        copy($this->connection->dbName, str_replace('.db', '.backup.db', $this->connection->dbName));

        return;
      case DBConnection::MySQL:
    }
  }

  function restore(): void {
    switch ($this->connection->driver) {
      case DBConnection::SQLite:
        $copy = str_replace('.db', '.backup.db', $this->connection->dbName);

        copy($copy, $this->connection->dbName);
        unlink($copy);

        return;
      case DBConnection::MySQL:

        return;
    }
  }
}
