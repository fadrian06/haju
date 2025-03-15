<?php

declare(strict_types=1);

namespace App\Repositories\Domain;

use App\Models\ConsultationCauseCategory;

/** @implements Repository<ConsultationCauseCategory> */
interface ConsultationCauseCategoryRepository extends Repository {
  public function getById(int $id): ?ConsultationCauseCategory;
}
