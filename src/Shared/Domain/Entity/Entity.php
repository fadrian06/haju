<?php



namespace HAJU\Shared\Domain\Entity;

use DateTimeImmutable;
use DateTimeInterface;

abstract class Entity
{
  public function __construct(
    public readonly string $id,
    private readonly DateTimeImmutable $createdAt
  ) {
    // ...
  }

  public function getCreatedAt(): DateTimeInterface
  {
    return $this->createdAt;
  }
}
