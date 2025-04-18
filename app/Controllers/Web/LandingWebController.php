<?php

declare(strict_types=1);

namespace App\Controllers\Web;

final readonly class LandingWebController {
  private function __construct() {
  }

  public static function showLanding(): void {
    renderPage('landing', 'Hospital Antonio José Uzcátegui', [], 'guest');
  }
}
