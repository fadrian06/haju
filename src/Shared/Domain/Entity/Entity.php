<?php

declare(strict_types=1);

namespace HAJU\Shared\Domain\Entity;

use DateTimeImmutable;
use DateTimeInterface;

abstract class Entity
{
  public readonly DateTimeInterface $createdAt;

  public function __construct(
    public readonly string $id,
    DateTimeImmutable $createdAt
  ) {
    $this->createdAt = $createdAt;
  }
}
