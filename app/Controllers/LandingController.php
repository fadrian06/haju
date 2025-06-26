<?php



namespace HAJU\Controllers;

final readonly class LandingController
{
  public function showLanding(): void
  {
    renderPage('landing', 'Hospital Antonio José Uzcátegui', [], 'guest');
  }
}
