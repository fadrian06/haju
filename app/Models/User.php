<?php

declare(strict_types=1);

namespace HAJU\Models;

use HAJU\Errors\UserDoNotHaveActiveDepartmentsError;
use HAJU\Errors\UserNotActiveError;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class User extends Model
{
  public function role(): BelongsTo
  {
    return $this->belongsTo(Role::class);
  }

  public function instructionLevel(): BelongsTo
  {
    return $this->belongsTo(InstructionLevel::class);
  }

  public function registeredBy(): BelongsTo
  {
    return $this->belongsTo(self::class);
  }

  public function usersRegistered(): HasMany
  {
    return $this->hasMany(self::class);
  }

  public function patientsRegistered(): HasMany
  {
    return $this->hasMany(Patient::class);
  }

  public function doctorsRegistered(): HasMany
  {
    return $this->hasMany(Doctor::class);
  }

  /**
   * @return BelongsToMany<Department>
   */
  public function departmentsAssigned(): BelongsToMany
  {
    return $this->belongsToMany(Department::class);
  }

  public function ensureThatIsActive(): self
  {
    if (!$this->attributes['is_active']) {
      throw new UserNotActiveError;
    }

    return $this;
  }

  public function ensureHasActiveDepartments(): self
  {
    $disactivadedDepartment = $this
      ->departmentsAssigned()
      ->firstWhere('is_active', false);

    if ($disactivadedDepartment) {
      throw new UserDoNotHaveActiveDepartmentsError;
    }

    return $this;
  }
}
