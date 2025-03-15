<?php

declare(strict_types=1);

namespace App\Repositories\Domain;

use App\Models\Contracts\Model;
use App\Repositories\Exceptions\ConnectionException;

/** @template T of Model */
interface Repository {
  /**
   * @return array<int, T>
   * @throws ConnectionException
   */
  public function getAll(): array;

  /** @throws ConnectionException */
  public function getRowsCount(): int;
  /**
   * @return ?T
   * @throws ConnectionException
   */
  public function getById(int $id): ?Model;
}
