<?php

declare(strict_types=1);

namespace HAJU\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Department extends Model
{
  public function usersAssigned(): BelongsToMany
  {
    return $this->belongsToMany(User::class);
  }

  public function consultations(): HasMany
  {
    return $this->hasMany(Consultation::class);
  }
}
