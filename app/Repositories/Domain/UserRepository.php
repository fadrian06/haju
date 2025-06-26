<?php

namespace HAJU\Repositories\Domain;

use HAJU\Models\User;
use HAJU\Repositories\Exceptions\RepositoryException;
use HAJU\Repositories\Exceptions\DuplicatedAvatarsException;
use HAJU\Repositories\Exceptions\DuplicatedEmailsException;
use HAJU\Repositories\Exceptions\DuplicatedIdCardException;
use HAJU\Repositories\Exceptions\DuplicatedNamesException;
use HAJU\Repositories\Exceptions\DuplicatedPhonesException;

/** @extends Repository<User> */
interface UserRepository extends Repository
{
  public function getAll(User ...$exclude): array;

  /** @throws RepositoryException */
  public function getByIdCard(int $idCard): ?User;

  public function thereAreUsers(): bool;

  /**
   * @throws RepositoryException
   * @throws DuplicatedIdCardException
   * @throws DuplicatedNamesException
   * @throws DuplicatedPhonesException
   * @throws DuplicatedEmailsException
   * @throws DuplicatedAvatarsException
   */
  public function save(User $user): void;
}
