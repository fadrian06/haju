<?php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;

return new class {
  public function __invoke()
  {
    Manager::schema()->dropIfExists('departments');

    Manager::schema()->create('departments', function (Blueprint $blueprint): void {
      $blueprint->id();
      $blueprint->timestamps();
      $blueprint->string('name')->unique();
      $blueprint->boolean('belongs_to_external_consultation');
      $blueprint->boolean('is_active');
      $blueprint->string('icon_url')->nullable();
    });
  }
};
