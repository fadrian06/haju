<?php

declare(strict_types=1);

namespace App\Repositories\Domain;

use App\Models\User;
use App\Repositories\Exceptions\ConnectionException;
use App\Repositories\Exceptions\DuplicatedAvatarsException;
use App\Repositories\Exceptions\DuplicatedEmailsException;
use App\Repositories\Exceptions\DuplicatedIdCardException;
use App\Repositories\Exceptions\DuplicatedNamesException;
use App\Repositories\Exceptions\DuplicatedPhonesException;

/** @extends Repository<User> */
interface UserRepository extends Repository {
  public function getAll(User ...$exclude): array;

  /** @throws ConnectionException */
  public function getByIdCard(int $idCard): ?User;
  public function getById(int $id): ?User;

  /**
   * @throws ConnectionException
   * @throws DuplicatedIdCardException
   * @throws DuplicatedNamesException
   * @throws DuplicatedPhonesException
   * @throws DuplicatedEmailsException
   * @throws DuplicatedAvatarsException
   */
  public function save(User $user): void;
}
