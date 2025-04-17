<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Role extends Model {
  protected $table = 'appointments';

  public function usersAssigned(): HasMany {
    return $this->hasMany(User::class);
  }
}
