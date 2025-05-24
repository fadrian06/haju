<?php

declare(strict_types=1);

namespace HAJU\InstructionLevels\Application;

use HAJU\InstructionLevels\Domain\InstructionLevelRepository;

final readonly class InstructionLevelRemover
{
  public function __construct(private InstructionLevelRepository $repository)
  {
    // ...
  }

  public function __invoke(string $id): void
  {
    $instructionLevel = $this->repository->getById($id);
    $this->repository->remove($instructionLevel);
  }
}
