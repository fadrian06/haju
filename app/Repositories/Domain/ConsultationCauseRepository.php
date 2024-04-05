<?php

namespace App\Repositories\Domain;

use App\Models\ConsultationCause;
use Generator;

/** @implements Repository<ConsultationCause> */
interface ConsultationCauseRepository extends Repository {
  function getById(int $id): ?ConsultationCause;
  /** @return Generator<int, ConsultationCause> */
  function getAllWithGenerator(): Generator;
}
