<?php

namespace App\Controllers\Web;

use App;

class SessionWebController {
  static function logOut(): void {
    session_start();
    session_destroy();

    App::redirect('/ingresar');
  }

  static function showLogin(): void {
    session_start();

    if (key_exists('userId', $_SESSION)) {
      App::redirect('/');

      return;
    }

    $error = $_SESSION['error'] ?? null;
    $message = $_SESSION['message'] ?? null;

    unset($_SESSION['error']);
    unset($_SESSION['message']);

    App::render('pages/login', compact('error', 'message'), 'content');
    App::render('layouts/base', ['title' => 'Ingreso']);
  }

  static function handleLogin(): void {
    $user = App::userRepository()->getByIdCard((int) App::request()->data['id_card']);

    session_start();

    if (!$user?->checkPassword(App::request()->data['password'])) {
      $_SESSION = ['error' => '❌ Cédula o contraseña incorrecta'];

      App::redirect('/ingresar');

      return;
    }

    $_SESSION = ['userId' => $user->getId()];

    App::redirect('/');
  }
}
