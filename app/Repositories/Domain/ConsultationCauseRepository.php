<?php

declare(strict_types=1);

namespace App\Repositories\Domain;

use App\Models\ConsultationCause;
use App\Models\ConsultationCauseCategory;
use Generator;

/** @implements Repository<ConsultationCause> */
interface ConsultationCauseRepository extends Repository {
  /** @return Generator<int, ConsultationCause> */
  public function getAllWithGenerator(): Generator;

  /** @return array<int, ConsultationCause> */
  public function getAllByCategory(ConsultationCauseCategory $category): array;
}
