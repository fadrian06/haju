<?php

declare(strict_types=1);

namespace App\Controllers\Web;

use App;
use App\Models\User;
use App\Repositories\Domain\DepartmentRepository;
use App\Repositories\Domain\UserRepository;
use App\ValueObjects\AdultBirthDate;
use App\ValueObjects\Appointment;
use App\ValueObjects\Date;
use App\ValueObjects\Exceptions\InvalidDateException;
use App\ValueObjects\Exceptions\InvalidPhoneException;
use App\ValueObjects\Gender;
use App\ValueObjects\InstructionLevel;
use App\ValueObjects\Phone;
use Error;
use Leaf\Http\Session;
use PharIo\Manifest\Email;
use PharIo\Manifest\InvalidEmailException;
use Throwable;

class UserWebController extends Controller {
  private readonly DepartmentRepository $departmentRepository;
  private readonly UserRepository $userRepository;

  public function __construct() {
    parent::__construct();

    $this->departmentRepository = App::departmentRepository();
    $this->userRepository = App::userRepository();
  }

  public function showRegister(): void {
    App::renderPage('register', 'Regístrate');
  }

  public function handleRegister(): void {
    [$appointment, $urlToRedirect, $urlWhenFail] = match (true) {
      !$this->loggedUser => [Appointment::Director, '/ingresar', '/registrate'],
      $this->loggedUser->appointment === Appointment::Director => [Appointment::Coordinator, '/usuarios', '/usuarios'],
      $this->loggedUser->appointment === Appointment::Coordinator => [Appointment::Secretary, '/usuarios', '/usuarios']
    };

    Session::set('lastData', $this->data->getData());

    try {
      if ($this->data['password'] !== $this->data['confirm_password']) {
        throw new Error('La contraseña y su confirmación no coinciden');
      }

      if (!in_array($this->data['gender'], Gender::values())) {
        throw new Error(sprintf('El género es requerido y válido (%s)', implode(', ', Gender::values())));
      }

      if (!in_array($this->data['instruction_level'], InstructionLevel::values())) {
        throw new Error(sprintf('El nivel de instrucción es requerido y válido (%s)', implode(', ', InstructionLevel::values())));
      }

      if (
        $this->loggedUser
        && $this->loggedUser->appointment === Appointment::Director
        && !$this->data['departments']
      ) {
        throw new Error('Debe asignar al menos 1 departamento');
      }

      $profileImageUrlPath = self::ensureThatFileIsSaved(
        'profile_image',
        'profile_image_url',
        $this->data['id_card'],
        'avatars',
        'La foto de perfil es requerida'
      );

      $user = new User(
        $this->data['first_name'],
        $this->data['second_name'],
        $this->data['first_last_name'],
        $this->data['second_last_name'],
        AdultBirthDate::from($this->data['birth_date'], '-'),
        Gender::from($this->data['gender']),
        $appointment,
        InstructionLevel::from($this->data['instruction_level']),
        (int) $this->data['id_card'],
        $this->data['password'],
        new Phone($this->data['phone']),
        new Email($this->data['email']),
        $this->data['address'],
        $profileImageUrlPath,
        true,
        $this->loggedUser
      );

      $departments = [];

      foreach ($this->data['departments'] ?? [] as $departmentID) {
        $departments[] = $this->departmentRepository->getById($departmentID);
      }

      if ($departments) {
        $user->assignDepartments(...$departments);
      }

      $this->userRepository->save($user);
      self::setMessage('Usuario registrado exitósamente');
      Session::unset('lastData');
      App::redirect($urlToRedirect);

      exit;
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

  public function showPasswordReset(): void {
    App::renderPage('forgot-pass', 'Recuperar contraseña (1/2)');
  }

  public function handlePasswordReset(): void {
    if ($this->data['id_card']) {
      $user = $this->userRepository->getByIdCard($this->data['id_card']);

      if ($user) {
        App::renderPage(
          'change-pass',
          'Recuperar contraseña (2/2)',
          compact('user')
        );

        exit;
      }

      self::setError('Cédula incorrecta');
      App::redirect('/recuperar');

      exit;
    }

    $user = $this->userRepository
      ->getById($this->data['id'])
      ->setPassword($this->data['password']);

    $this->userRepository->save($user);
    self::setMessage('Contraseña actualizada exitósamente');
    App::redirect('/ingresar');
  }

  public function showProfile(): void {
    App::renderPage('profile', 'Mi perfil', [
      'showPasswordChangeModal' => false
    ], 'main');
  }

  public function showEditProfile(): void {
    App::renderPage('edit-profile', 'Editar perfil', [], 'main');
  }

  public function handleEditProfile(): void {
    try {
      $profileImageUrlPath = '';

      if (App::request()->files['profile_image']['size']) {
        $profileImageUrlPath = self::ensureThatFileIsSaved(
          'profile_image',
          'profile_image_url',
          $this->data['id_card'],
          'avatars',
          'La foto de perfil es requerida'
        );
      }

      $this->loggedUser->setIdCard($this->data['id_card']);
      $this->loggedUser->instructionLevel = InstructionLevel::from($this->data['instruction_level']);
      $this->loggedUser->setFirstName($this->data['first_name']);
      $this->data['second_name'] && $this->loggedUser->setSecondName($this->data['second_name']);
      $this->loggedUser->setFirstLastName($this->data['first_last_name']);
      $this->data['second_last_name'] && $this->loggedUser->setSecondLastName($this->data['second_last_name']);
      $this->loggedUser->setAddress($this->data['address']);
      $this->loggedUser->birthDate = Date::from($this->data['birth_date'], '-');
      $this->loggedUser->gender = Gender::from($this->data['gender']);
      $this->loggedUser->email = new Email($this->data['email']);
      $this->loggedUser->phone = new Phone($this->data['phone']);

      if ($profileImageUrlPath) {
        $this->loggedUser->profileImagePath = $profileImageUrlPath;
      }

      $this->userRepository->save($this->loggedUser);
      self::setMessage('Perfil actualizado exitósamente');
    } catch (Throwable $error) {
      self::setError($error);
    }

    App::redirect('/perfil/editar');
  }

  public function showUsers(): void {
    $users = $this->userRepository->getAll($this->loggedUser);

    $departments = $this->loggedUser->appointment === Appointment::Director
      ? $this->departmentRepository->getAll()
      : [];

    $filteredUsers = array_filter($users, fn(User $user): bool => $user->appointment->isLowerOrEqualThan($this->loggedUser->appointment));

    if ($this->loggedUser->appointment === Appointment::Coordinator) {
      $filteredUsers = array_filter($filteredUsers, fn(User $user): bool => $user->appointment->isHigherThan($this->loggedUser->appointment) || (
        $user->appointment === Appointment::Secretary
        && $user->registeredBy->isEqualTo($this->loggedUser)
      ));
    }

    $usersNumber = count($filteredUsers);

    App::renderPage(
      'users',
      "Usuarios ({$usersNumber})",
      ['users' => $filteredUsers, ...compact('departments')],
      'main'
    );
  }

  public function handleToggleStatus(int $id): void {
    try {
      $user = $this->userRepository->getById($id);

      if (!$user->registeredBy->isEqualTo($this->loggedUser)) {
        throw new Error('Acceso denegado');
      }

      $user->toggleStatus();
      $this->userRepository->save($user);
      self::setMessage("Usuario {$user->firstName} {$user->getActiveStatusText()} exitósamente");
    } catch (Throwable $error) {
      self::setError($error);
    }

    App::redirect($user->appointment === Appointment::Director ? '/salir' : '/usuarios');
  }

  public function handlePasswordChange(): void {
    try {
      if (!$this->loggedUser->checkPassword($this->data['old_password'])) {
        throw new Error('La contraseña anterior es incorrecta');
      }

      if ($this->data['new_password'] !== $this->data['confirm_password']) {
        throw new Error('La nueva contraseña y su confirmación no coinciden');
      }

      if ($this->data['new_password'] === $this->data['old_password']) {
        throw new Error('La nueva contraseña no puede ser igual a la anterior');
      }

      $this->loggedUser->setPassword($this->data['new_password']);
      $this->userRepository->save($this->loggedUser);
      self::setMessage('Contraseña actualizada exitósamente');
      $this->session->set('mustChangePassword', false);
    } catch (Throwable $error) {
      self::setError($error);
    }

    App::redirect('/perfil');
  }
}
