<?php

declare(strict_types=1);

namespace App\Repositories\Domain;

use App\OldModels\ConsultationCause;
use App\OldModels\ConsultationCauseCategory;
use Generator;

/**
 * @implements Repository<ConsultationCause>
 */
interface ConsultationCauseRepository extends Repository {
  /**
   * @return Generator<int, ConsultationCause>
   */
  public function getAllWithGenerator(): Generator;

  /**
   * @return ConsultationCause[]
   */
  public function getAllByCategory(ConsultationCauseCategory $category): array;
}
