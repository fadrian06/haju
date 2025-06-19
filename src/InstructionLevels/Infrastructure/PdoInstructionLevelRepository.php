<?php

declare(strict_types=1);

namespace HAJU\InstructionLevels\Infrastructure;

use DateTimeImmutable;
use Exception;
use HAJU\InstructionLevels\Domain\InstructionLevel;
use HAJU\InstructionLevels\Domain\InstructionLevelRepository;
use PDO;

final readonly class PdoInstructionLevelRepository implements InstructionLevelRepository
{
  public function __construct(private PDO $pdo)
  {
    $this->pdo->exec('
      CREATE TABLE IF NOT EXISTS instruction_levels (
        id VARCHAR(255) PRIMARY KEY,
        created_at DATETIME NOT NULL,
        name VARCHAR(255) NOT NULL UNIQUE,
        abbreviation VARCHAR(255) NOT NULL UNIQUE
      );
    ');
  }

  public function getAll(): array
  {
    $stmt = $this->pdo->query('SELECT * FROM instruction_levels ORDER BY name');
    $instructionLevels = $stmt->fetchAll(PDO::FETCH_FUNC, $this->mapper(...));

    return $instructionLevels;
  }


  public function getById(string $id): ?InstructionLevel
  {
    $stmt = $this->pdo->prepare('
      SELECT * FROM instruction_levels
      WHERE id = ?
    ');

    $stmt->bindValue(1, $id);
    $stmt->execute();
    $row = $stmt->fetch(SQLITE3_ASSOC);

    if (!$stmt->rowCount()) {
      return null;
    }

    return $this->mapper(
      strval($row['id']),
      $row['created_at'],
      $row['name'],
      $row['abbreviation'],
    );
  }

  public function save(InstructionLevel $instructionLevel): void
  {
    $stmt = $this->pdo->prepare('
      INSERT INTO instruction_levels
      VALUES (:id, :createdAt, :name, :abbreviation)
      ON CONFLICT(id) DO UPDATE SET
        name = excluded.name,
        abbreviation = excluded.abbreviation
    ');

    $stmt->bindValue('id', $instructionLevel->id);

    $stmt->bindValue(
      'createdAt',
      $instructionLevel->createdAt->format('Y-m-d H:i:s')
    );

    $stmt->bindValue('name', $instructionLevel->getName());
    $stmt->bindValue('abbreviation', $instructionLevel->getAbbreviation());

    try {
      $stmt->execute();
    } catch (Exception $exception) {
      $message = $exception->getMessage();

      if (str_contains($message, 'instruction_levels.name')) {
        throw new Exception(
          "Ya existe un nivel de instrucción de nombre \"{$instructionLevel->getName()}\""
        );
      }

      if (str_contains($message, 'instruction_levels.abbreviation')) {
        throw new Exception(
          "Ya existe un nivel de instrucción con la abreviatura \"{$instructionLevel->getAbbreviation()}\""
        );
      }

      throw $exception;
    }
  }

  public function remove(InstructionLevel $instructionLevel): void
  {
    $stmt = $this
      ->pdo
      ->prepare('DELETE FROM instruction_levels WHERE id = ?');

    $stmt->bindValue(1, $instructionLevel->id);
    $stmt->execute();
  }

  private function mapper(
    string $id,
    string $createdAt,
    string $name,
    string $abbreviation
  ): InstructionLevel {
    $instructionLevel = new InstructionLevel(
      $id,
      new DateTimeImmutable($createdAt),
      $name,
      $abbreviation
    );

    return $instructionLevel;
  }
}
