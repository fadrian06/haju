<?php

namespace App\Controllers\Web;

use App;
use App\Models\Appointment;
use App\Models\Date;
use App\Models\Exceptions\InvalidPhoneException;
use App\Models\Gender;
use App\Models\InstructionLevel;
use App\Models\Phone;
use App\Models\User;
use App\Repositories\Exceptions\DuplicatedIdCardException;
use App\Repositories\Exceptions\DuplicatedNamesException;
use Error;
use PharIo\Manifest\Email;
use PharIo\Manifest\InvalidEmailException;
use PharIo\Manifest\InvalidUrlException;
use PharIo\Manifest\Url;

class UserWebController extends Controller {
  static function showRegister(): void {
    App::render('pages/register', [], 'content');
    App::render('layouts/base', ['title' => 'Regístrate']);
  }

  static function handleRegister(): void {
    $data = App::request()->data;
    $files = App::request()->files;

    $loggedUser = App::view()->get('user');

    assert($loggedUser === null || $loggedUser instanceof User);

    [$appointment, $urlToRedirect, $urlWhenFail] = match (true) {
      !$loggedUser => [Appointment::Director, '/ingresar', '/registrate'],
      $loggedUser->appointment === Appointment::Director => [Appointment::Coordinator, '/usuarios', '/usuarios'],
      $loggedUser->appointment === Appointment::Coordinator => [Appointment::Secretary, '/usuarios', '/usuarios']
    };

    $temporalProfileImagePath = $files['profile_image']['tmp_name'];
    $profileImageName = $files['profile_image']['name'];
    $profileImagePath = dirname(__DIR__, 3) . "/assets/img/avatars/{$profileImageName}";
    $profileImageUrlPath = App::request()->scheme . '://' . App::request()->host . App::get('root') . "/assets/img/avatars/{$profileImageName}";

    copy($temporalProfileImagePath, $profileImagePath);

    $user = new User(
      $data['first_name'],
      $data['second_name'],
      $data['first_last_name'],
      $data['second_last_name'],
      Date::from($data['birth_date'], '-'),
      Gender::from($data['gender']),
      $appointment,
      InstructionLevel::from($data['instruction_level']),
      (int) $data['id_card'],
      $data['password'],
      new Phone($data['phone']),
      new Email($data['email']),
      $data['address'],
      new Url($profileImageUrlPath)
    );

    $departments = [];

    foreach ($data['departments'] ?? [] as $departmentID) {
      $departments[] = App::departmentRepository()->getById((int) $departmentID);
    }

    if ($departments) {
      $user->assignDepartments(...$departments);
    }

    try {
      App::userRepository()->save($user);
      self::setMessage('Usuario registrado exitósamente');
      App::redirect($urlToRedirect);

      return;
    } catch (DuplicatedNamesException) {
      self::setError("Usuario \"{$user->getFullName()}\" ya existe");
    } catch (DuplicatedIdCardException) {
      self::setError("Cédula \"{$user->idCard}\" ya existe");
    } catch (InvalidPhoneException) {
      self::setError("Teléfono inválido \"{$data['phone']}\"");
    } catch (InvalidEmailException) {
      self::setError("Correo inválido \"{$data['email']}\"");
    } catch (InvalidUrlException) {
      self::setError("URL inválida \"{$data['avatar']}\"");
    }

    App::redirect($urlWhenFail);
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
    $loggedUser = App::view()->get('user');
    $data = App::request()->data;

    assert($loggedUser instanceof User);

    $loggedUser->firstName = $data['first_name'];
    $loggedUser->lastName = $data['last_name'];
    $loggedUser->address = $data['address'];
    $loggedUser->birthDate = Date::from($data['birth_date'], '-');
    $loggedUser->gender = Gender::from($data['gender']);
    $loggedUser->email = $data['email'] ? new Email($data['email']) : null;
    $loggedUser->phone = $data['phone'] ? new Phone($data['phone']) : null;

    App::userRepository()->save($loggedUser);
    self::setMessage('Perfil actualizado exitósamente');
    App::redirect('/perfil/editar');
  }

  static function showUsers(): void {
    $loggedUser = App::view()->get('user');

    assert($loggedUser instanceof User);

    $users = App::userRepository()->getAll($loggedUser);
    $departments = $loggedUser->role === Role::Director ? App::departmentRepository()->getAll() : [];

    $filteredUsers = array_filter($users, function (User $user) use ($loggedUser): bool {
      return $user->role->getLevel() <= $loggedUser->role->getLevel();
    });

    $usersNumber = count($filteredUsers);

    App::renderPage(
      'users',
      "Usuarios ($usersNumber)",
      ['users' => $filteredUsers, 'departments' => $departments],
      'main'
    );
  }

  static function handleToggleStatus(string $id): void {
    $user = App::userRepository()->getById((int) $id);
    $user->isActive = !$user->isActive;

    App::userRepository()->save($user);
    App::redirect('/usuarios');
  }

  static function handlePasswordChange(): void {
    $loggedUser = App::view()->get('user');
    $data = App::request()->data;

    assert($loggedUser instanceof User);

    try {
      if (!$loggedUser->checkPassword($data['old_password'])) {
        throw new Error('La contraseña anterior es incorrecta');
      }

      if ($data['new_password'] !== $data['confirm_password']) {
        throw new Error('La nueva contraseña y su confirmación no coinciden');
      }

      if ($data['new_password'] === $data['old_password']) {
        throw new Error('La nueva contraseña no puede ser igual a la anterior');
      }

      $loggedUser->setPassword($data['new_password']);
      App::userRepository()->save($loggedUser);
      self::setMessage('Contraseña actualizada exitósamente');
    } catch (Error $error) {
      self::setError($error->getMessage());
    }

    App::redirect('/perfil');
  }
}
