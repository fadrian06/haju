<?php

declare(strict_types=1);

namespace App\Controllers\Web;

final class ReportsWebController {
  public function showEpi11(): void {
    renderPage('reports/epi11', 'EPI-11', [], 'minimal');
  }

  public function showEpi15(): void {
    renderPage('reports/epi15', 'EPI-15', [], 'minimal');
  }
}
