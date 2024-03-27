<?php

namespace App\Controllers\Web;

use App;
use App\Models\Appointment;
use App\Models\Date;
use App\Models\Exceptions\InvalidDateException;
use App\Models\Exceptions\InvalidPhoneException;
use App\Models\Gender;
use App\Models\InstructionLevel;
use App\Models\Phone;
use App\Models\User;
use Error;
use PharIo\Manifest\Email;
use PharIo\Manifest\InvalidEmailException;
use Throwable;

class UserWebController extends Controller {
  static function showRegister(): void {
    App::renderPage('register', 'Regístrate');
  }

  static function handleRegister(): void {
    $data = App::request()->data;

    $loggedUser = App::view()->get('user');

    assert($loggedUser === null || $loggedUser instanceof User);

    [$appointment, $urlToRedirect, $urlWhenFail] = match (true) {
      !$loggedUser => [Appointment::Director, '/ingresar', '/registrate'],
      $loggedUser->appointment === Appointment::Director => [Appointment::Coordinator, '/usuarios', '/usuarios'],
      $loggedUser->appointment === Appointment::Coordinator => [Appointment::Secretary, '/usuarios', '/usuarios']
    };

    try {
      if ($data['password'] !== $data['confirm_password']) {
        throw new Error('La contraseña y su confirmación no coinciden');
      }

      if (!in_array($data['gender'], Gender::values())) {
        throw new Error(sprintf('El género es requerido y válido (%s)', join(', ', Gender::values())));
      }

      if (!in_array($data['instruction_level'], InstructionLevel::values())) {
        throw new Error(sprintf('El nivel de instrucción es requerido y válido (%s)', join(', ', InstructionLevel::values())));
      }

      if (!App::request()->files['profile_image']['size']) {
        throw new Error('La foto de perfil es requerida');
      }

      if ($loggedUser && $loggedUser->appointment === Appointment::Director && !$data['departments']) {
        throw new Error('Debe asignar al menos 1 departamento');
      }

      $profileImageUrlPath = self::uploadFile('profile_image', 'avatars');

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
        $profileImageUrlPath
      );

      $departments = [];

      foreach ($data['departments'] ?? [] as $departmentID) {
        $departments[] = App::departmentRepository()->getById((int) $departmentID);
      }

      if ($departments) {
        $user->assignDepartments(...$departments);
      }

      App::userRepository()->save($user);
      self::setMessage('Usuario registrado exitósamente');

      exit(App::redirect($urlToRedirect));
    } catch (InvalidDateException) {
      self::setError('La fecha de nacimiento es requerida y válida');
    } catch (InvalidPhoneException) {
      self::setError('El teléfono es requerido y válido');
    } catch (InvalidEmailException) {
      self::setError('El correo es requerido y válido');
    } catch (Throwable $error) {
      self::setError($error);
    }

    App::redirect($urlWhenFail);
  }

  static function showPasswordReset(): void {
    App::renderPage('forgot-pass', 'Recuperar contraseña (1/2)');
  }

  static function handlePasswordReset(): void {
    if (App::request()->data['id_card']) {
      $user = App::userRepository()->getByIdCard((int) App::request()->data['id_card']);

      if ($user) {
        exit(App::renderPage('change-pass', 'Recuperar contraseña (2/2)', compact('user')));
      }

      self::setError('Cédula incorrecta');

      exit(App::redirect('/recuperar'));
    }

    $user = App::userRepository()->getById(App::request()->data['id'])
      ->setPassword(App::request()->data['password']);

    App::userRepository()->save($user);
    self::setMessage('Contraseña actualizada exitósamente');
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

    try {
      $profileImageUrlPath = '';

      if (App::request()->files['profile_image']['size']) {
        $profileImageUrlPath = self::uploadFile('profile_image', 'avatars');
      }

      $loggedUser->setIdCard($data['id_card']);
      $loggedUser->instructionLevel = InstructionLevel::from($data['instruction_level']);
      $loggedUser->setFirstName($data['first_name']);
      $data['second_name'] && $loggedUser->setSecondName($data['second_name']);
      $loggedUser->setFirstLastName($data['first_last_name']);
      $data['second_last_name'] && $loggedUser->setSecondLastName($data['second_last_name']);
      $loggedUser->setAddress($data['address']);
      $loggedUser->birthDate = Date::from($data['birth_date'], '-');
      $loggedUser->gender = Gender::from($data['gender']);
      $loggedUser->email = new Email($data['email']);
      $loggedUser->phone = new Phone($data['phone']);

      if ($profileImageUrlPath) {
        $loggedUser->profileImagePath = $profileImageUrlPath;
      }

      App::userRepository()->save($loggedUser);
      self::setMessage('Perfil actualizado exitósamente');
    } catch (Throwable $error) {
      self::setError($error);
    }

    App::redirect('/perfil/editar');
  }

  static function showUsers(): void {
    $loggedUser = App::view()->get('user');

    assert($loggedUser instanceof User);

    $users = App::userRepository()->getAll($loggedUser);
    $departments = $loggedUser->appointment === Appointment::Director
      ? App::departmentRepository()->getAll()
      : [];

    $filteredUsers = array_filter($users, function (User $user) use ($loggedUser): bool {
      return $user->appointment->getLevel() <= $loggedUser->appointment->getLevel();
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
    $user->toggleActiveStatus();

    App::userRepository()->save($user);
    App::redirect($user->appointment === Appointment::Director ? '/salir' : '/usuarios');
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
    } catch (Throwable $error) {
      self::setError($error);
    }

    App::redirect('/perfil');
  }
}
