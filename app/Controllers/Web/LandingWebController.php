<?php

declare(strict_types=1);

namespace App\Controllers\Web;

use App;

final readonly class LandingWebController {
  public function showLanding(): void {
    App::renderPage('landing', 'Hospital Antonio José Uzcátegui', [], 'guest');
  }
}
