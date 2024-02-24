<?php

namespace App\Controllers\Web;

use App;

class HomeWebController {
  static function index(): void {
    session_start();

    if (!key_exists('userId', $_SESSION)) {
      App::redirect('/ingresar');

      return;
    }

    $user = App::userRepository()->getById((int) $_SESSION['userId']);

    if (!$user) {
      App::redirect('/salir');

      return;
    }

    App::render('pages/home', [], 'content');
    App::render('layouts/main', ['title' => 'Inicio', ...compact('user')]);
  }
}
