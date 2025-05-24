<?php

declare(strict_types=1);

namespace HAJU\InstructionLevels\Domain;

use DateTimeImmutable;
use HAJU\Shared\Domain\Entity\Entity;

final class InstructionLevel extends Entity
{
  public function __construct(
    string $id,
    DateTimeImmutable $createdAt,
    private string $name,
    private string $abbreviation,
  ) {
    parent::__construct($id, $createdAt);
    // ...
  }

  public function getName(): string
  {
    return str_replace('/A', '/a', mb_convert_case($this->name, MB_CASE_TITLE));
  }

  public function getAbbreviation(): string
  {
    return str_replace(
      '/A',
      '/a',
      mb_convert_case($this->abbreviation, MB_CASE_TITLE),
    );
  }

  public function update(string $name, string $abbreviation): void
  {
    $this->name = $name;
    $this->abbreviation = $abbreviation;
  }
}
