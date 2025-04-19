<?php

declare(strict_types=1);

namespace HAJU\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class ConsultationCauseCategory extends Model
{
  public function causes(): HasMany
  {
    return $this->hasMany(ConsultationCause::class);
  }

  public function parentCategory(): BelongsTo
  {
    return $this->belongsTo(self::class);
  }
}
