<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class ConsultationCauseCategory extends Model {
  public function causes(): HasMany {
    return $this->hasMany(ConsultationCause::class, 'category_id');
  }

  public function parentCategory(): BelongsTo {
    return $this->belongsTo(self::class, 'top_category_id');
  }
}
