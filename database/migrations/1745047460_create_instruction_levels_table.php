<?php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;

return new class {
  public function __invoke()
  {
    Manager::schema()->dropIfExists('instruction_levels');

    Manager::schema()->create('instruction_levels', function (Blueprint $blueprint): void {
      $blueprint->id();
      $blueprint->timestamps();
      $blueprint->string('name')->unique();
      $blueprint->string('abbreviation')->unique();
    });
  }
};
