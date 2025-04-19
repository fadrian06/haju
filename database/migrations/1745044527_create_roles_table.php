<?php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;

return new class {
  public function __invoke()
  {
    Manager::schema()->dropIfExists('roles');

    Manager::schema()->create('roles', function (Blueprint $blueprint): void {
      $blueprint->id();
      $blueprint->timestamps();
      $blueprint->string('name')->unique();
    });
  }
};
