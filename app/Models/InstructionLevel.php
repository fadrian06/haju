<?php

declare(strict_types=1);

namespace HAJU\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class InstructionLevel extends Model
{
  public function usersAssigned(): HasMany
  {
    return $this->hasMany(User::class);
  }
}
