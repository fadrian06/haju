<?php

declare(strict_types=1);

namespace HAJU\Repositories\Infraestructure\PDO;

use HAJU\Models\ConsultationCause;
use HAJU\Models\ConsultationCauseCategory;
use HAJU\Repositories\Domain\ConsultationCauseCategoryRepository;
use HAJU\Repositories\Domain\ConsultationCauseRepository;
use Generator;
use PDO;

final class PDOConsultationCauseRepository
extends PDORepository implements ConsultationCauseRepository {
  private const FIELDS = <<<sql
  id, short_name as shortName, extended_name as extendedName, variant, code,
  category_id as categoryId, weekly_cases_limit as weeklyLimit
  sql;

  public function __construct(
    PDO $pdo,
    string $baseUrl,
    private readonly ConsultationCauseCategoryRepository $categoryRepository,
  ) {
    parent::__construct($pdo, $baseUrl);
  }

  protected static function getTable(): string {
    return 'consultation_causes';
  }

  public function getAll(): array {
    return $this->ensureIsConnected()
      ->query(sprintf('SELECT %s FROM %s', self::FIELDS, self::getTable()))
      ->fetchAll(PDO::FETCH_FUNC, [$this, 'mapper']);
  }

  public function getAllByCategory(ConsultationCauseCategory $category): array {
    $stmt = $this->ensureIsConnected()->prepare(sprintf(
      'SELECT %s FROM %s WHERE category_id = ?',
      self::FIELDS,
      self::getTable()
    ));

    $stmt->execute([$category->id]);

    return $stmt->fetchAll(PDO::FETCH_FUNC, [$this, 'mapper']);
  }

  public function getAllWithGenerator(): Generator {
    $stmt = $this->ensureIsConnected()
      ->query(sprintf('SELECT %s FROM %s', self::FIELDS, self::getTable()));

    while (is_array($cause = $stmt->fetch(PDO::FETCH_ASSOC))) {
      yield $this->mapper(...$cause);
    }
  }

  public function getById(int $id): ?ConsultationCause {
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
    string $shortName,
    ?string $extendedName,
    ?string $variant,
    ?string $code,
    int $categoryId,
    ?int $weeklyLimit
  ): ConsultationCause {
    $consultationCause = new ConsultationCause(
      $this->categoryRepository->getById($categoryId),
      $shortName,
      $extendedName,
      $variant,
      $code,
      $weeklyLimit
    );

    $consultationCause->setId($id);

    return $consultationCause;
  }
}
