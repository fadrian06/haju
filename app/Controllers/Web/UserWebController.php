<?php

namespace App\Controllers\Web;

use App;
use App\Models\GenrePrefix;
use App\Models\User;
use App\Repositories\Exceptions\DuplicatedIdCardException;
use App\Repositories\Exceptions\DuplicatedNamesException;

class UserWebController {
  static function showRegister(): void {
    session_start();

    $error = $_SESSION['error'] ?? null;
    unset($_SESSION['error']);

    App::render('pages/register', compact('error'), 'content');
    App::render('layouts/base', ['title' => 'Regístrate']);
  }

  static function handleRegister(): void {
    $data = App::request()->data;

    $user = new User(
      $data['first_name'],
      $data['last_name'],
      $data['speciality'],
      GenrePrefix::tryFrom($data['prefix'] ?? ''),
      (int) $data['id_card'],
      $data['password'],
      $data['avatar']
    );

    session_start();

    try {
      App::userRepository()->save($user);
      $_SESSION['message'] = '✔ Usuario registrado exitósamente';
      App::redirect('/ingresar');

      return;
    } catch (DuplicatedNamesException) {
      $_SESSION['error'] = "❌ Usuario \"{$user->getFullName()}\" ya existe";
    } catch (DuplicatedIdCardException) {
      $_SESSION['error'] = "❌ Cédula \"{$user->idCard}\" ya existe";
    }

    App::redirect('/registrate');
  }

  static function showPasswordReset(): void {
    session_start();

    $error = $_SESSION['error'] ?? null;

    unset($_SESSION['error']);

    App::render('pages/forgot-pass', compact('error'), 'content');
    App::render('layouts/base', ['title' => 'Recuperar contraseña (1/2)']);
  }

  static function handlePasswordReset(): void {
    session_start();

    if (App::request()->data['id_card']) {
      $user = App::userRepository()->getByIdCard((int) App::request()->data['id_card']);

      if ($user) {
        App::render('pages/change-pass', compact('user'), 'content');
        App::render('layouts/base', ['title' => 'Recuperar contraseña (2/2)']);

        return;
      }

      $_SESSION['error'] = '❌ Cédula incorrecta';

      App::redirect('/recuperar');

      return;
    }

    $user = App::userRepository()->getById(App::request()->data['id'])
      ->setPassword(App::request()->data['password']);

    App::userRepository()->save($user);

    $_SESSION['message'] = '✔ Contraseña actualizada exitósamente';

    App::redirect('/ingresar');
  }

  static function showProfile(): void {
  }
}
