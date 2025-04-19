<?php

declare(strict_types=1);

use HAJU\Models\ConsultationCauseCategory;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;

return new class {
  public function __invoke()
  {
    Manager::schema()->dropIfExists('consultation_cause_categories');

    Manager::schema()->create('consultation_cause_categories', function (Blueprint $blueprint): void {
      $blueprint->id();
      $blueprint->timestamps();
      $blueprint->string('short_name')->unique();
      $blueprint->string('extended_name')->nullable();
      $blueprint->foreignIdFor(ConsultationCauseCategory::class)->nullable();
    });
  }
};
