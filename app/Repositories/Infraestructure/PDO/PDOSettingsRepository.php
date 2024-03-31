<?php

namespace App\Repositories\Infraestructure\PDO;

use App\Models\Hospital;
use App\Repositories\Domain\SettingsRepository;

class PDOSettingsRepository extends PDORepository implements SettingsRepository {
  function getHospital(): Hospital {
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

  function backupExists(): bool {
    switch ($this->connection->driver) {
      case DBDriver::SQLite:
        return file_exists(str_replace('.db', '.backup.db', $this->connection->dbName));

      case DBDriver::MySQL:
        return file_exists(__DIR__ . '/../../../database/backup.mysql.sql');
    }
  }

  function backup(): void {
    switch ($this->connection->driver) {
      case DBDriver::SQLite:
        copy($this->connection->dbName, str_replace('.db', '.backup.db', $this->connection->dbName));

        return;
      case DBDriver::MySQL:
    }
  }

  function restore(): void {
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

  function save(Hospital $hospital): void {
    $data = [
      'name' => $hospital->getName(),
      'asic' => $hospital->getAsic(),
      'type' => $hospital->getType(),
      'place' => $hospital->getPlace(),
      'parish' => $hospital->getParish(),
      'municipality' => $hospital->getMunicipality(),
      'healthDepartment' => $hospital->getHealthDepartment(),
      'region' => $hospital->getRegion()
    ];

    file_put_contents(__DIR__ . '/hospital.json', json_encode($data, JSON_PRETTY_PRINT));
  }
}
