<?php

declare(strict_types=1);

const ROOT_PATH = __DIR__ . '/../../..';

$migrationPath = ROOT_PATH . '/database/migrations/' . time() . '_' . $argv[1] . '.php';

$migrationTemplate = <<<'php'
<?php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Schema\Blueprint;

return new class {
  public function __invoke()
  {
    Manager::schema()->dropIfExists('table');

    Manager::schema()->create('table', function (Blueprint $blueprint): void {
      $blueprint->id();
      $blueprint->timestamps();
    });
  }
};
php;

file_put_contents($migrationPath, $migrationTemplate);
