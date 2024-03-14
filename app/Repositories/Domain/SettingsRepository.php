<?php

namespace App\Repositories\Domain;

interface SettingsRepository {
  function backupExists(): bool;
  function backup(): void;
  function restore(): void;
}
