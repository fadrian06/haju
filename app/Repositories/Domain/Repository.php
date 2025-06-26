<?php

namespace HAJU\Repositories\Domain;

use HAJU\Models\Contracts\Model;
use HAJU\Repositories\Exceptions\RepositoryException;

/** @template T of Model */
interface Repository
{
  /**
   * @return array<int, T>
   * @throws RepositoryException
   */
  public function getAll(): array;

  /** @throws RepositoryException */
  public function getRowsCount(): int;

  /**
   * @return ?T
   * @throws RepositoryException
   */
  public function getById(int $id): ?Model;
}
