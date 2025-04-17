<?php

declare(strict_types=1);

namespace App\Repositories\Domain;

use App\OldModels\Hospital;

interface SettingsRepository {
  public function getHospital(): Hospital;
  public function backupExists(): bool;
  public function backup(): string;
  public function restore(): void;
  public function restoreFromScript(string $script): void;
  public function save(Hospital $hospital): void;
}
