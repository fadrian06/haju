<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Hospitalization extends Model {
  public function patient(): BelongsTo {
    return $this->belongsTo(Patient::class);
  }

  public function doctor(): BelongsTo {
    return $this->belongsTo(Doctor::class);
  }
}
