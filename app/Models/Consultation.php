<?php

declare(strict_types=1);

namespace HAJU\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Consultation extends Model
{
  public function patient(): BelongsTo
  {
    return $this->belongsTo(Patient::class);
  }

  public function cause(): BelongsTo
  {
    return $this->belongsTo(ConsultationCause::class);
  }

  public function department(): BelongsTo
  {
    return $this->belongsTo(Department::class);
  }

  public function doctor(): BelongsTo
  {
    return $this->belongsTo(Doctor::class);
  }
}
