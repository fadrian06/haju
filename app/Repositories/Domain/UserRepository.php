<?php

namespace App\Repositories\Domain;

use App\Models\User;
use App\Repositories\Exceptions\ConnectionException;
use App\Repositories\Exceptions\DuplicatedIdCardException;

interface UserRepository {
  /**
   * @return array<int, User>
   * @throws ConnectionException
   */
  function getAll(): array;

  /** @throws ConnectionException */
  function getByIdCard(int $idCard): ?User;

  /** @throws ConnectionException */
  function getById(int $id): ?User;

  /**
   * @throws ConnectionException
   * @throws DuplicatedIdCardException
   */
  function save(User $user): void;
}
