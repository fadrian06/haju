<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Patient extends Model {
  public function registeredBy(): BelongsTo {
    return $this->belongsTo(User::class);
  }

  public function consultations(): HasMany {
    return $this->hasMany(Consultation::class);
  }

  public function hospitalizations(): HasMany {
    return $this->hasMany(Hospitalization::class);
  }
}
