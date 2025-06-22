<?php

declare(strict_types=1);

namespace HAJU\InstructionLevels\Domain;

use DateTimeImmutable;
use HAJU\Shared\Domain\Entity\Entity;
use InvalidArgumentException;

final class InstructionLevel extends Entity
{
  private string $name;
  private string $abbreviation;

  public function __construct(
    string $id,
    DateTimeImmutable $createdAt,
    string $name,
    string $abbreviation,
  ) {
    parent::__construct($id, $createdAt);

    $this->setName($name);
    $this->setAbbreviation($abbreviation);
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function getAbbreviation(): string
  {
    return $this->abbreviation;
  }

  public function update(string $name, string $abbreviation): void
  {
    $this->setName($name);
    $this->setAbbreviation($abbreviation);
  }

  private function setName(string $name): void
  {
    if (!$name) {
      throw new InvalidArgumentException('El nombre no puede estar vacío');
    }

    $this->name = str_replace(
      '/A',
      '/a',
      mb_convert_case($name, MB_CASE_TITLE),
    );
  }

  private function setAbbreviation(string $abbreviation): void
  {
    if (!$abbreviation) {
      throw new InvalidArgumentException('La abreviatura no puede estar vacía');
    }

    $this->abbreviation = str_replace(
      '/A',
      '/a',
      mb_convert_case($abbreviation, MB_CASE_TITLE),
    );
  }
}
