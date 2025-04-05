<?php

declare(strict_types=1);

namespace App\Controllers\Web;

final readonly class LandingWebController {
  public function showLanding(): void {
    renderPage('landing', 'Hospital Antonio José Uzcátegui', [], 'guest');
  }
}
