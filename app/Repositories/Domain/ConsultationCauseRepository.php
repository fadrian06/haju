<?php

namespace HAJU\Repositories\Domain;

use HAJU\Models\ConsultationCause;
use HAJU\Models\ConsultationCauseCategory;
use Generator;

/** @implements Repository<ConsultationCause> */
interface ConsultationCauseRepository extends Repository
{
  /** @return Generator<int, ConsultationCause> */
  public function getAllWithGenerator(): Generator;

  /** @return array<int, ConsultationCause> */
  public function getAllByCategory(ConsultationCauseCategory $category): array;
}
