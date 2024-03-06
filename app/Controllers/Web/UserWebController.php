<?php

namespace App\Controllers\Web;

use App;
use App\Models\Date;
use App\Models\Exceptions\InvalidPhoneException;
use App\Models\Gender;
use App\Models\Phone;
use App\Models\ProfessionPrefix;
use App\Models\Role;
use App\Models\User;
use App\Repositories\Exceptions\DuplicatedIdCardException;
use App\Repositories\Exceptions\DuplicatedNamesException;
use PharIo\Manifest\Email;
use PharIo\Manifest\InvalidEmailException;
use PharIo\Manifest\InvalidUrlException;
use PharIo\Manifest\Url;

class UserWebController {
  static function showRegister(): void {
    App::render('pages/register', [], 'content');
    App::render('layouts/base', ['title' => 'Regístrate']);
  }

  static function handleRegister(): void {
    $data = App::request()->data;

    $user = new User(
      $data['first_name'],
      $data['last_name'],
      Date::from($data['birth_date'], '-'),
      Gender::from($data['gender']),
      Role::Director,
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
    } catch (InvalidPhoneException) {
      App::session()->set('error', "❌ Teléfono inválido \"{$data['phone']}\"");
    } catch (InvalidEmailException) {
      App::session()->set('error', "❌ Correo inválido \"{$data['email']}\"");
    } catch (InvalidUrlException) {
      App::session()->set('error', "❌ URL inválida \"{$data['avatar']}\"");
    }

    App::redirect('/registrate');
  }

  static function showPasswordReset(): void {
    App::render('pages/forgot-pass', [], 'content');
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
    App::renderPage('profile', 'Mi perfil', [], 'main');
  }

  static function showEditProfile(): void {
    App::renderPage('edit-profile', 'Editar perfil', [], 'main');
  }

  static function handleEditProfile(): void {
    App::json(App::request()->data);
  }

  static function showUsers(): void {
    $users = App::userRepository()->getAll(App::view()->get('user'));
    $usersNumber = count($users);

    App::renderPage('users', "Usuarios ($usersNumber)", compact('users'), 'main');
  }

  static function handleToggleStatus(string $id): void {
    $user = App::userRepository()->getById((int) $id);
    $user->isActive = !$user->isActive;

    App::userRepository()->save($user);
    App::redirect('/usuarios');
  }
}
