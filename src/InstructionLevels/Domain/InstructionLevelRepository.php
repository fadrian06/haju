<?php

declare(strict_types=1);

namespace HAJU\InstructionLevels\Domain;

use Throwable;

interface InstructionLevelRepository
{
  /** @return InstructionLevel[] */
  public function getAll(): array;

  /** @throws Throwable */
  public function save(InstructionLevel $instructionLevel): void;

  public function remove(InstructionLevel $instructionLevel): void;
}
