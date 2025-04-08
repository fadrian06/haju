<?php

declare(strict_types=1);

namespace App\Repositories\Infraestructure\Files;

use App\Enums\DBDriver;
use App\Models\Hospital;
use App\Repositories\Domain\SettingsRepository;
use App\Repositories\Infraestructure\PDO\Connection;
use PDO;
use PDOException;

final readonly class FilesSettingsRepository implements SettingsRepository {
  public function __construct(private Connection $connection) {
  }

  public function getHospital(): Hospital {
    $info = json_decode(file_get_contents(__DIR__ . '/hospital.json'), true);

    return new Hospital(
      $info['name'],
      $info['asic'],
      $info['type'],
      $info['place'],
      $info['parish'],
      $info['municipality'],
      $info['healthDepartment'],
      $info['region']
    );
  }

  public function backupExists(): bool {
    switch ($this->connection->driver) {
      case DBDriver::SQLite:
        return file_exists(str_replace('.db', '.backup.db', $this->connection->dbName));

      case DBDriver::MySQL:
        return file_exists(__DIR__ . '/../../../database/backup.mysql.sql');
    }
  }

  public function backup(): string {
    switch ($this->connection->driver) {
      case DBDriver::SQLite:
        copy($this->connection->dbName, str_replace('.db', '.backup.db', $this->connection->dbName));
        $script = $this->generateSqliteScript();
        $backupPath = str_replace('.db', '.backup.sql', $this->connection->dbName);

        file_put_contents(
          $backupPath,
          $script
        );

        return $backupPath;
      case DBDriver::MySQL:
      default:
        return '';
    }
  }

  public function restore(): void {
    switch ($this->connection->driver) {
      case DBDriver::SQLite:
        $copy = str_replace('.db', '.backup.db', $this->connection->dbName);

        copy($copy, $this->connection->dbName);
        unlink($copy);

        return;
      case DBDriver::MySQL:

        return;
    }
  }

  public function restoreFromScript(string $script): void {
    $pdo = $this->connection->instance();

    if ($this->connection->driver === DBDriver::SQLite) {
      foreach (explode(';', $script) as $statement) {
        if ($statement) {
          try {
            $pdo->query($statement);
          } catch (PDOException) {
          }
        }
      }
    }
  }

  public function save(Hospital $hospital): void {
    $data = [
      'name' => $hospital->name,
      'asic' => $hospital->asic,
      'type' => $hospital->type,
      'place' => $hospital->place,
      'parish' => $hospital->parish,
      'municipality' => $hospital->municipality,
      'healthDepartment' => $hospital->healthDepartment,
      'region' => $hospital->region
    ];

    file_put_contents(__DIR__ . '/hospital.json', json_encode($data, JSON_PRETTY_PRINT));
  }

  private function generateSqliteScript(): string {
    $pdo = $this->connection->instance();
    $tablesQuery = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'");
    $tables = $tablesQuery->fetchAll(PDO::FETCH_COLUMN);
    $sqlScript = '';

    foreach ($tables as $table) {
      if ($table === 'sqlite_sequence') {
        continue;
      }

      $createTableQuery = $pdo->query("SELECT sql FROM sqlite_master WHERE type='table' AND name='{$table}'");
      $createTableSql = $createTableQuery->fetch(PDO::FETCH_COLUMN);
      $createTableSql = str_replace('CREATE TABLE', 'CREATE TABLE IF NOT EXISTS', $createTableSql);
      $sqlScript .= $createTableSql . ";\n\n";
      $rowsQuery = $pdo->query("SELECT * FROM {$table}");
      $rows = $rowsQuery->fetchAll(PDO::FETCH_ASSOC);
      $allValues = [];

      foreach ($rows as $row) {
        $columns = array_keys($row);
        $values = array_values($row);

        $columnsList = implode(', ', array_map(
          static fn($col): string =>  "`{$col}`",
          $columns
        ));

        $valuesList = implode(', ', array_map(
          static function ($val) use ($pdo): string {
            if ($val === null) {
              return 'null';
            }

            return $pdo->quote($val);
          },
          $values
        ));

        if (!in_array("({$valuesList})", $allValues)) {
          $allValues[] = "({$valuesList})";
        }
      }

      if ($allValues !== []) {
        $sqlScript .= "INSERT INTO `{$table}` ({$columnsList}) VALUES " . implode(', ', $allValues) . ";\n";
      }

      $sqlScript .= "\n";
    }

    return $sqlScript;
  }
}
