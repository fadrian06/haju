<?php

declare(strict_types=1);

namespace App\Repositories\Domain;

use App\Models\User;
use App\Repositories\Exceptions\RepositoryException;
use App\Repositories\Exceptions\DuplicatedAvatarsException;
use App\Repositories\Exceptions\DuplicatedEmailsException;
use App\Repositories\Exceptions\DuplicatedIdCardException;
use App\Repositories\Exceptions\DuplicatedNamesException;
use App\Repositories\Exceptions\DuplicatedPhonesException;

/**
 * @extends Repository<User>
 */
interface UserRepository extends Repository {
  public function getAll(User ...$exclude): array;

  /**
   * @throws RepositoryException
   */
  public function getByIdCard(int $idCard): ?User;

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
