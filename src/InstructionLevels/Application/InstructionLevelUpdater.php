<?php



namespace HAJU\InstructionLevels\Application;

use HAJU\InstructionLevels\Domain\InstructionLevelRepository;

final readonly class InstructionLevelUpdater
{
  public function __construct(private InstructionLevelRepository $repository)
  {
    // ...
  }

  public function __invoke(string $id, string $name, string $abbreviation): void
  {
    $instructionLevel = $this->repository->getById($id);
    $instructionLevel->update($name, $abbreviation);
    $this->repository->save($instructionLevel);
  }
}
