<?php

namespace App\Repositories\Domain;

use App\Models\ConsultationCause;

/** @implements Repository<ConsultationCause> */
interface ConsultationCauseRepository extends Repository {
  function getById(int $id): ?ConsultationCause;
}
