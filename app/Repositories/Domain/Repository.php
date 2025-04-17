<?php

declare(strict_types=1);

namespace App\Repositories\Domain;

use App\OldModels\Contracts\Model;
use App\Repositories\Exceptions\RepositoryException;

/**
 * @template T of Model
 */
interface Repository {
  /**
   * @return array<int, T>
   * @throws RepositoryException
   */
  public function getAll(): array;

  /**
   * @throws RepositoryException
   */
  public function getRowsCount(): int;

  /**
   * @return ?T
   * @throws RepositoryException
   */
  public function getById(int $id): ?Model;
}
