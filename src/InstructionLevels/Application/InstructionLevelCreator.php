<?php

declare(strict_types=1);

namespace HAJU\InstructionLevels\Application;

use DateTimeImmutable;
use HAJU\InstructionLevels\Domain\InstructionLevel;
use HAJU\InstructionLevels\Domain\InstructionLevelRepository;

final readonly class InstructionLevelCreator
{
  public function __construct(private InstructionLevelRepository $repository)
  {
    // ...
  }

  public function __invoke(string $name, string $abbreviation): void
  {
    $instructionLevel = new InstructionLevel(
      uniqid(),
      new DateTimeImmutable(),
      $name,
      $abbreviation
    );

    $this->repository->save($instructionLevel);
  }
}
