<?php

declare(strict_types=1);

namespace HAJU\Repositories\Infraestructure\Files;

use HAJU\Enums\DBDriver;
use HAJU\Models\Hospital;
use HAJU\Repositories\Domain\SettingsRepository;
use PDO;
use PDOException;

final readonly class FilesSettingsRepository implements SettingsRepository {
  public function __construct(private PDO $pdo) {
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
    return match ($_ENV['DB_CONNECTION']) {
      DBDriver::SQLite => file_exists(str_replace('.db', '.backup.db', $_ENV['DB_DATABASE'])),
      DBDriver::MySQL => file_exists(__DIR__ . '/../../../database/backup.mysql.sql'),
      default => false,
    };
  }

  public function backup(): string {
    switch ($_ENV['DB_CONNECTION']) {
      case DBDriver::SQLite:
        copy($_ENV['DB_DATABASE'], str_replace('.db', '.backup.db', $_ENV['DB_DATABASE']));
        $script = $this->generateSqliteScript();
        $backupPath = str_replace('.db', '.backup.sql', $_ENV['DB_DATABASE']);

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
    switch ($_ENV['DB_CONNECTION']) {
      case DBDriver::SQLite:
        $copy = str_replace('.db', '.backup.db', $_ENV['DB_DATABASE']);

        copy($copy, $_ENV['DB_DATABASE']);
        unlink($copy);

        return;
      case DBDriver::MySQL:

        return;
    }
  }

  public function restoreFromScript(string $script): void {
    if ($_ENV['DB_CONNECTION'] === DBDriver::SQLite) {
      foreach (explode(';', $script) as $statement) {
        if ($statement) {
          $this->pdo->query($statement);
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
    $tablesQuery = $this->pdo->query("SELECT name FROM sqlite_master WHERE type='table'");
    $tables = $tablesQuery->fetchAll(PDO::FETCH_COLUMN);
    $sqlScript = '';

    foreach ($tables as $table) {
      if ($table === 'sqlite_sequence') {
        continue;
      }

      $createTableQuery = $this->pdo->query("SELECT sql FROM sqlite_master WHERE type='table' AND name='{$table}'");
      $createTableSql = $createTableQuery->fetch(PDO::FETCH_COLUMN);
      $createTableSql = str_replace('CREATE TABLE', 'CREATE TABLE IF NOT EXISTS', $createTableSql);
      $sqlScript .= $createTableSql . ";\n\n";
      $rowsQuery = $this->pdo->query("SELECT * FROM {$table}");
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
          function ($val): string {
            if ($val === null) {
              return 'null';
            }

            return $this->pdo->quote($val);
          },
          $values
        ));

        if (!in_array("({$valuesList})", $allValues, true)) {
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
