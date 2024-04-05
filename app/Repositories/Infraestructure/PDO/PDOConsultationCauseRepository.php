<?php

namespace App\Repositories\Infraestructure\PDO;

use App\Models\ConsultationCause;
use App\Repositories\Domain\ConsultationCauseRepository;
use Generator;
use PDO;

final class PDOConsultationCauseRepository
extends PDORepository implements ConsultationCauseRepository {
  private const FIELDS = <<<sql
  id, name, variant, code, category_id as categoryId
  sql;

  function __construct(
    Connection $connection,
    string $baseUrl,
    private readonly PDOConsultationCauseCategoryRepository $categoryRepository
  ) {
    parent::__construct($connection, $baseUrl);
  }

  protected static function getTable(): string {
    return 'consultation_causes';
  }

  function getAll(): array {
    return $this->ensureIsConnected()
      ->query(sprintf('SELECT %s FROM %s', self::FIELDS, self::getTable()))
      ->fetchAll(PDO::FETCH_FUNC, [$this, 'mapper']);
  }

  function getAllWithGenerator(): Generator {
    $stmt = $this->ensureIsConnected()
      ->query(sprintf('SELECT %s FROM %s', self::FIELDS, self::getTable()));

    while ($cause = $stmt->fetch(PDO::FETCH_ASSOC)) {
      yield $this->mapper(...$cause);
    }
  }

  function getById(int $id): ?ConsultationCause {
    $stmt = $this->ensureIsConnected()->prepare(sprintf(
      'SELECT %s FROM %s WHERE id = ?',
      self::FIELDS,
      self::getTable()
    ));

    $stmt->execute([$id]);

    return $stmt->fetchAll(PDO::FETCH_FUNC, [$this, 'mapper'])[0] ?? null;
  }

  private function mapper(
    int $id,
    string $name,
    ?string $variant,
    ?string $code,
    int $categoryId
  ): ConsultationCause {
    $cause = new ConsultationCause(
      $this->categoryRepository->getById($categoryId),
      $name,
      $variant,
      $code
    );

    $cause->setId($id);

    return $cause;
  }
}
