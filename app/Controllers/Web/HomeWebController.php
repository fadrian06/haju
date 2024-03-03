<?php

namespace App\Controllers\Web;

use App;

class HomeWebController {
  static function index(): void {
    if (
      !App::session()->get('userId')
      || !$user = App::userRepository()->getById((int) App::session()->get('userId'))
    ) {
      App::redirect('/salir');

      return;
    }

    App::render('pages/home', [], 'content');
    App::render('layouts/main', ['title' => 'Inicio', ...compact('user')]);
  }
}
