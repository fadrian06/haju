<?php

namespace App\Controllers\Web;

use App;
use App\Models\Date;
use App\Models\Gender;
use App\Models\Phone;
use App\Models\ProfessionPrefix;
use App\Models\Role;
use App\Models\User;
use App\Repositories\Exceptions\DuplicatedIdCardException;
use App\Repositories\Exceptions\DuplicatedNamesException;
use PharIo\Manifest\Email;
use PharIo\Manifest\Url;

class UserWebController {
  static function showRegister(): void {
    $error = App::session()->retrieve('error', null, true);

    App::render('pages/register', compact('error'), 'content');
    App::render('layouts/base', ['title' => 'Regístrate']);
  }

  static function handleRegister(): void {
    $data = App::request()->data;

    $user = new User(
      $data['first_name'],
      $data['last_name'],
      Date::from($data['birth_date'], '-'),
      Gender::from($data['gender']),
      Role::from($data['role']),
      $data['prefix'] ? ProfessionPrefix::from($data['prefix']) : null,
      (int) $data['id_card'],
      $data['password'],
      $data['phone'] ? new Phone($data['phone']) : null,
      $data['email'] ? new Email($data['email']) : null,
      $data['address'],
      $data['avatar'] ? new Url($data['avatar']) : null
    );

    try {
      App::userRepository()->save($user);
      App::session()->set('message', '✔ Usuario registrado exitósamente');
      App::redirect('/ingresar');

      return;
    } catch (DuplicatedNamesException) {
      App::session()->set('error', "❌ Usuario \"{$user->getFullName()}\" ya existe");
    } catch (DuplicatedIdCardException) {
      App::session()->set('error', "❌ Cédula \"{$user->idCard}\" ya existe");
    }

    App::redirect('/registrate');
  }

  static function showPasswordReset(): void {
    $error = App::session()->retrieve('error', null, true);

    App::render('pages/forgot-pass', compact('error'), 'content');
    App::render('layouts/base', ['title' => 'Recuperar contraseña (1/2)']);
  }

  static function handlePasswordReset(): void {
    if (App::request()->data['id_card']) {
      $user = App::userRepository()->getByIdCard((int) App::request()->data['id_card']);

      if ($user) {
        App::render('pages/change-pass', compact('user'), 'content');
        App::render('layouts/base', ['title' => 'Recuperar contraseña (2/2)']);

        return;
      }

      App::session()->set('error', '❌ Cédula incorrecta');
      App::redirect('/recuperar');

      return;
    }

    $user = App::userRepository()->getById(App::request()->data['id'])
      ->setPassword(App::request()->data['password']);

    App::userRepository()->save($user);
    App::session()->set('message', '✔ Contraseña actualizada exitósamente');
    App::redirect('/ingresar');
  }

  static function showProfile(): void {
    if (
      !App::session()->get('userId')
      || !$user = App::userRepository()->getById((int) App::session()->get('userId'))
    ) {
      App::redirect('/salir');

      return;
    }

    App::renderPage('profile', 'Mi perfil', compact('user'), 'main');
  }

  static function showEditProfile(): void {
    if (
      !App::session()->get('userId')
      || !$user = App::userRepository()->getById((int) App::session()->get('userId'))
    ) {
      App::redirect('/salir');

      return;
    }

    App::renderPage('edit-profile', 'Editar perfil', compact('user'), 'main');
  }

  static function handleEditProfile(): void {
    App::json(App::request()->data);
  }
}
