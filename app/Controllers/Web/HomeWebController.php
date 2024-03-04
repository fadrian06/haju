<?php

namespace App\Controllers\Web;

use App;

class HomeWebController {
  static function index(): void {
    App::render('pages/home', [], 'content');
    App::render('layouts/main', ['title' => 'Inicio']);
  }
}
