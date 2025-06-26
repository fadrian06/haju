<?php



namespace HAJU\Repositories\Domain;

use HAJU\Models\Hospital;

interface SettingsRepository
{
  public function getHospital(): Hospital;
  public function backupExists(): bool;
  public function backup(): string;
  public function restore(): void;
  public function restoreFromScript(string $script): void;
  public function save(Hospital $hospital): void;
}
