<?php

declare(strict_types=1);

namespace App\Repositories\Domain;

use App\Models\ConsultationCause;
use App\Models\ConsultationCauseCategory;
use Generator;

/** @implements Repository<ConsultationCause> */
interface ConsultationCauseRepository extends Repository {
  function getById(int $id): ?ConsultationCause;
  /** @return Generator<int, ConsultationCause> */
  function getAllWithGenerator(): Generator;
  /** @return array<int, ConsultationCause> */
  function getByCategory(ConsultationCauseCategory $category): array;
}
