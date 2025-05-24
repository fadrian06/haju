<?php

declare(strict_types=1);

namespace HAJU\InstructionLevels\Application;

use HAJU\InstructionLevels\Domain\InstructionLevel;
use HAJU\InstructionLevels\Domain\InstructionLevelRepository;

final readonly class InstructionLevelSearcher
{
  public function __construct(private InstructionLevelRepository $repository)
  {
    // ...
  }

  /** @return InstructionLevel[] */
  public function getAll(): array
  {
    return $this->repository->getAll();
  }
}
