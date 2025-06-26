<?php

namespace HAJU\Tests;

use DateTimeImmutable;
use Exception;
use HAJU\InstructionLevels\Domain\InstructionLevel;
use HAJU\InstructionLevels\Infrastructure\SqliteInstructionLevelRepository;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SQLite3;

final class SqliteInstructionLevelRepositoryTest extends TestCase
{
  private readonly SqliteInstructionLevelRepository $repository;

  protected function setUp(): void
  {
    $this->repository = new SqliteInstructionLevelRepository(
      new SQLite3(':memory:'),
    );
  }

  #[Test]
  public function itSavesANewValidInstructionLevel(): void
  {
    $validInstructionLevel = new InstructionLevel(
      uniqid(),
      new DateTimeImmutable(),
      'Name 1',
      'Abbreviation 1'
    );

    $this->repository->save($validInstructionLevel);

    $savedInstructionLevels = $this->repository->getAll();

    self::assertCount(1, $savedInstructionLevels);

    self::assertSame(
      $validInstructionLevel->id,
      $savedInstructionLevels[0]->id
    );

    self::assertSame(
      $validInstructionLevel->getCreatedAt()->format('Y-m-d H:i:s'),
      $savedInstructionLevels[0]->getCreatedAt()->format('Y-m-d H:i:s')
    );

    self::assertSame(
      $validInstructionLevel->getName(),
      $savedInstructionLevels[0]->getName()
    );

    self::assertSame(
      $validInstructionLevel->getAbbreviation(),
      $savedInstructionLevels[0]->getAbbreviation()
    );
  }

  #[Test]
  public function itFailsSavingDuplicatedNames(): void
  {
    $validInstructionLevel = new InstructionLevel(
      uniqid(),
      new DateTimeImmutable(),
      'Name 1',
      'Abbreviation 1'
    );

    self::expectException(Exception::class);

    self::expectExceptionMessage(
      'Ya existe un nivel de instrucción de nombre "Name 1"'
    );

    $this->repository->save($validInstructionLevel);

    $this->repository->save(
      new InstructionLevel(
        uniqid(),
        new DateTimeImmutable(),
        'Name 1',
        'Abbreviation 2'
      )
    );
  }

  #[Test]
  public function itFailsSavingDuplicatedAbbreviations(): void
  {
    $validInstructionLevel = new InstructionLevel(
      uniqid(),
      new DateTimeImmutable(),
      'Name 1',
      'Abbreviation 1'
    );

    self::expectException(Exception::class);
    self::expectExceptionMessage(
      'Ya existe un nivel de instrucción con la abreviatura "Abbreviation 1"'
    );

    $this->repository->save($validInstructionLevel);

    $this->repository->save(
      new InstructionLevel(
        uniqid(),
        new DateTimeImmutable(),
        'Name 2',
        'Abbreviation 1'
      )
    );
  }

  #[Test]
  public function itRemovesAnInstructionLevel(): void
  {
    $validInstructionLevel = new InstructionLevel(
      uniqid(),
      new DateTimeImmutable(),
      'Name 1',
      'Abbreviation 1'
    );

    $this->repository->save($validInstructionLevel);
    $this->repository->remove($validInstructionLevel);

    $savedInstructionLevels = $this->repository->getAll();

    self::assertCount(0, $savedInstructionLevels);
  }

  #[Test]
  public function itUpdatesAnInstructionLevel(): void
  {
    $validInstructionLevel = new InstructionLevel(
      uniqid(),
      new DateTimeImmutable(),
      'Name 1',
      'Abbreviation 1'
    );

    $this->repository->save($validInstructionLevel);

    $validInstructionLevel->update('Name 2', 'Abbreviation 2');
    $this->repository->save($validInstructionLevel);

    $savedInstructionLevels = $this->repository->getAll();

    self::assertCount(1, $savedInstructionLevels);

    self::assertSame(
      'Name 2',
      $savedInstructionLevels[0]->getName()
    );

    self::assertSame(
      'Abbreviation 2',
      $savedInstructionLevels[0]->getAbbreviation()
    );
  }

  #[Test]
  public function itFailsSavingAnUpdatedInstructionLevelWithDuplicatedName(): void
  {
    $this->repository->save(new InstructionLevel(
      uniqid(),
      new DateTimeImmutable(),
      'Name 1',
      'Abbreviation 1'
    ));

    $this->repository->save(new InstructionLevel(
      uniqid(),
      new DateTimeImmutable(),
      'Name 2',
      'Abbreviation 2'
    ));

    $savedInstructionLevels = $this->repository->getAll();
    [$firstInstructionLevel] = $savedInstructionLevels;

    $firstInstructionLevel->update('Name 2', 'Abbreviation 1');

    self::expectException(Exception::class);

    self::expectExceptionMessage(
      'Ya existe un nivel de instrucción de nombre "Name 2"'
    );

    $this->repository->save($firstInstructionLevel);
  }

  #[Test]
  public function itFailsSavingAnUpdatedInstructionLevelWithDuplicatedAbbreviation(): void
  {
    $this->repository->save(new InstructionLevel(
      uniqid(),
      new DateTimeImmutable(),
      'Name 1',
      'Abbreviation 1'
    ));

    $this->repository->save(new InstructionLevel(
      uniqid(),
      new DateTimeImmutable(),
      'Name 2',
      'Abbreviation 2'
    ));

    $savedInstructionLevels = $this->repository->getAll();
    [$firstInstructionLevel] = $savedInstructionLevels;

    $firstInstructionLevel->update('Name 1', 'Abbreviation 2');

    self::expectException(Exception::class);

    self::expectExceptionMessage(
      'Ya existe un nivel de instrucción con la abreviatura "Abbreviation 2"'
    );

    $this->repository->save($firstInstructionLevel);
  }
}
