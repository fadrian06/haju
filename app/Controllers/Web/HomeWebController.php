<?php

namespace App\Controllers\Web;

use App;

class HomeWebController {
  static function index(): void {
    $usersNumber = count(App::userRepository()->getAll(App::view()->get('user')));
    $departmentsNumber = count(App::departmentRepository()->getAll());

    App::render('pages/home', compact('usersNumber', 'departmentsNumber'), 'content');
    App::render('layouts/main', ['title' => 'Inicio']);
  }
}
