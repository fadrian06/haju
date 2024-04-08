<?php

namespace App\Repositories\Infraestructure\PDO;

use App\Models\ConsultationCauseCategory;
use App\Repositories\Domain\ConsultationCauseCategoryRepository;
use PDO;

final class PDOConsultationCauseCategoryRepository
extends PDORepository implements ConsultationCauseCategoryRepository {
  private const FIELDS = <<<sql
  id, short_name as shortName, extended_name as extendedName,
  top_category_id as parentCategoryId
  sql;

  protected static function getTable(): string {
    return 'consultation_cause_categories';
  }

  function getAll(): array {
    return $this->ensureIsConnected()
      ->query(sprintf('SELECT %s FROM %s WHERE id != 1', self::FIELDS, self::getTable()))
      ->fetchAll(PDO::FETCH_FUNC, [$this, 'mapper']);
  }

  function getById(int $id): ?ConsultationCauseCategory {
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
    ?int $parentCategoryId
  ): ConsultationCauseCategory {
    $category = new ConsultationCauseCategory(
      $shortName,
      $extendedName,
      $parentCategoryId ? $this->getById($parentCategoryId) : null
    );

    $category->setId($id);

    return $category;
  }
}
