<?php

namespace App\Controllers\Web;

use App;

final class ReportsWebController {
  function showEpi11(): void {
    App::renderPage('reports/epi11', 'EPI-11', [], 'minimal');
  }
}
