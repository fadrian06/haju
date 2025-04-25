<?php

declare(strict_types=1);

namespace HAJU\Controllers;

final readonly class LandingWebController
{
  public function showLanding(): void
  {
    renderPage('landing', 'Hospital Antonio José Uzcátegui', [], 'guest');
  }
}
