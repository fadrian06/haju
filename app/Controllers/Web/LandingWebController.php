<?php

namespace App\Controllers\Web;

use App;

final readonly class LandingWebController {
  function showLanding(): void {
    App::renderPage('landing', 'Hospital Antonio José Uzcátegui', [], 'guest');
  }
}
