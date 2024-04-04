<?php

namespace App\Repositories\Domain;

use App\Models\Contracts\Model;
use App\Repositories\Exceptions\ConnectionException;

/** @template T of Model */
interface Repository {
  /**
   * @return array<int, T>
   * @throws ConnectionException
   */
  function getAll(): array;

  /** @throws ConnectionException */
  function getRowsCount(): int;
  /**
   * @return ?T
   * @throws ConnectionException
   */
  function getById(int $id): ?Model;
}
