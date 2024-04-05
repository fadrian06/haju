<?php

namespace App\Repositories\Domain;

use App\Models\Hospital;

interface SettingsRepository {
  function getHospital(): Hospital;
  function backupExists(): bool;
  function backup(): void;
  function restore(): void;
  function save(Hospital $hospital): void;
}
