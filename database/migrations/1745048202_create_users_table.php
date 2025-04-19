<?php

declare(strict_types=1);

use HAJU\Models\InstructionLevel;
use HAJU\Models\Role;
use HAJU\Models\User;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;

return new class {
  public function __invoke()
  {
    Manager::schema()->dropIfExists('users');

    Manager::schema()->create('users', function (Blueprint $blueprint): void {
      $blueprint->id();
      $blueprint->timestamps();
      $blueprint->string('first_name');
      $blueprint->string('second_name')->nullable();
      $blueprint->string('first_last_name');
      $blueprint->string('second_last_name')->nullable();
      $blueprint->date('birth_date');
      $blueprint->enum('gender', ['Masculino', 'Femenino']);
      $blueprint->integer('id_card')->unique();
      $blueprint->string('password');
      $blueprint->string('phone')->unique();
      $blueprint->string('email')->unique();
      $blueprint->string('address')->nullable();
      $blueprint->string('avatar_url')->nullable();
      $blueprint->boolean('is_active');
      $blueprint->foreignIdFor(Role::class);
      $blueprint->foreignIdFor(InstructionLevel::class);
      $blueprint->foreignIdFor(User::class)->nullable();

      $blueprint->unique(['first_name', 'second_name', 'first_last_name', 'second_last_name']);
    });
  }
};
