<?php

declare(strict_types=1);

namespace HAJU\Controllers;

use HAJU\Models\User;
use HAJU\Repositories\Domain\DepartmentRepository;
use HAJU\Repositories\Domain\UserRepository;
use HAJU\Repositories\Exceptions\DuplicatedNamesException;
use HAJU\ValueObjects\AdultBirthDate;
use HAJU\Enums\Appointment;
use HAJU\ValueObjects\Date;
use HAJU\ValueObjects\Exceptions\InvalidDateException;
use HAJU\ValueObjects\Exceptions\InvalidPhoneException;
use HAJU\Enums\Gender;
use HAJU\Enums\InstructionLevel;
use HAJU\ValueObjects\Phone;
use Error;
use Flight;
use Leaf\Http\Session;
use PharIo\Manifest\Email;
use PharIo\Manifest\InvalidEmailException;
use Throwable;
use ZxcvbnPhp\Zxcvbn;

final readonly class UserWebController extends Controller
{
  private const INSECURE_PASSWORD_STRENGTH_LEVEL = 2;

  public function __construct(
    private DepartmentRepository $departmentRepository,
    private UserRepository $userRepository,
    private Zxcvbn $passwordValidator,
  ) {
    parent::__construct();
  }

  public function showRegister(): void
  {
    renderPage('register', 'Regístrate');
  }

  public function handleRegister(): void
  {
    [$appointment, $urlToRedirect, $urlWhenFail] = match (true) {
      !$this->loggedUser => [
        Appointment::Director,
        '/ingresar',
        '/registrate'
      ],
      $this->loggedUser->appointment->isDirector() => [
        Appointment::Coordinator,
        '/usuarios',
        '/usuarios'
      ],
      $this->loggedUser->appointment->isCoordinator() => [
        Appointment::Secretary,
        '/usuarios',
        '/usuarios'
      ]
    };

    Session::set('lastData', $this->data->getData());

    try {
      if ($this->data['password'] !== $this->data['confirm_password']) {
        throw new Error('La contraseña y su confirmación no coinciden');
      }

      if (!in_array($this->data['gender'], Gender::values(), true)) {
        throw new Error(sprintf(
          'El género es requerido y válido (%s)',
          implode(', ', Gender::values()),
        ));
      }

      if (!in_array($this->data['instruction_level'], InstructionLevel::values(), true)) {
        throw new Error(sprintf(
          'El nivel de instrucción es requerido y válido (%s)',
          implode(', ', InstructionLevel::values()),
        ));
      }

      if ($this->loggedUser && $this->loggedUser->appointment->isDirector() && $this->data['departments'] === []) {
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
        $departments[] = $this->departmentRepository->getById(intval($departmentID));
      }

      if ($departments) {
        $user->assignDepartments(...$departments);
      }

      $this->userRepository->save($user);
      self::setMessage("{$user->getParsedAppointment()} registrado exitósamente");
      Session::unset('lastData');
      Flight::redirect($urlToRedirect);

      return;
    } catch (InvalidDateException) {
      self::setError('La fecha de nacimiento es requerida y válida');
    } catch (InvalidPhoneException) {
      self::setError('El teléfono es requerido y válido');
    } catch (InvalidEmailException) {
      self::setError('El correo es requerido y válido');
    } catch (DuplicatedNamesException) {
      self::setError('El nombre y apellido ya están registrados');
    } catch (Throwable $error) {
      self::setError($error);
    }

    Flight::redirect($urlWhenFail);
  }

  public function showPasswordReset(): void
  {
    renderPage('forgot-pass', 'Recuperar contraseña (1/2)');
  }

  public function handlePasswordReset(): void
  {
    if ($this->data['id_card'] !== null) {
      $user = $this->userRepository->getByIdCard((int) $this->data['id_card']);

      if ($user) {
        renderPage(
          'change-pass',
          'Recuperar contraseña (2/2)',
          compact('user')
        );

        return;
      }

      self::setError('Cédula incorrecta');
      Flight::redirect('/recuperar');

      return;
    }

    $user = $this->userRepository
      ->getById(intval($this->data['id']))
      ->setPassword($this->data['password']);

    $this->userRepository->save($user);
    self::setMessage('Contraseña actualizada exitósamente');
    Flight::redirect('/ingresar');
  }

  public function showProfile(): void
  {
    renderPage('profile', 'Mi perfil', [
      'showPasswordChangeModal' => false
    ], 'main');
  }

  public function showEditProfile(): void
  {
    renderPage('edit-profile', 'Editar perfil', [], 'main');
  }

  public function handleEditProfile(): void
  {
    try {
      $profileImageUrlPath = '';

      if (Flight::request()->files['profile_image']['size'] > 0) {
        $profileImageUrlPath = self::ensureThatFileIsSaved(
          'profile_image',
          'profile_image_url',
          $this->data['id_card'],
          'avatars',
          'La foto de perfil es requerida'
        );
      }

      $this->loggedUser->setIdCard(intval($this->data['id_card']));
      $this->loggedUser->instructionLevel = InstructionLevel::from($this->data['instruction_level']);
      $this->loggedUser->setFirstName($this->data['first_name']);
      $this->loggedUser->setSecondName($this->data['second_name']);
      $this->loggedUser->setFirstLastName($this->data['first_last_name']);
      $this->loggedUser->setSecondLastName($this->data['second_last_name']);
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

    Flight::redirect('/perfil/editar');
  }

  public function showUsers(): void
  {
    $users = $this->userRepository->getAll($this->loggedUser);

    $departments = $this->loggedUser->appointment === Appointment::Director
      ? $this->departmentRepository->getAll()
      : [];

    $filteredUsers = array_filter(
      $users,
      fn(User $user): bool => $user->appointment->isLowerOrEqualThan($this->loggedUser->appointment)
    );

    if ($this->loggedUser->appointment === Appointment::Coordinator) {
      $filteredUsers = array_filter(
        $filteredUsers,
        fn(User $user): bool => $user->appointment->isHigherThan($this->loggedUser->appointment) || (
          $user->appointment->isSecretary()
          && $user->registeredBy->isEqualTo($this->loggedUser)
        )
      );
    }

    $usersNumber = count($filteredUsers);

    renderPage(
      'users',
      "Usuarios ({$usersNumber})",
      ['users' => $filteredUsers, ...compact('departments')],
      'main'
    );
  }

  public function handleToggleStatus(int $id): void
  {
    try {
      $user = $this->userRepository->getById($id);

      if (!$user->appointment->isDirector()) {
        throw new Error('Acceso denegado');
      }

      $user->toggleStatus();
      $this->userRepository->save($user);
      self::setMessage("Usuario {$user->firstName} {$user->getActiveStatusText()} exitósamente");
    } catch (Throwable $error) {
      self::setError($error);
    }

    Flight::redirect($user->appointment === Appointment::Director ? '/salir' : '/usuarios');
  }

  public function handlePasswordChange(): void
  {
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

      $passwordStrength = $this->passwordValidator->passwordStrength($this->data['new_password']);

      if ($passwordStrength['score'] <= self::INSECURE_PASSWORD_STRENGTH_LEVEL || $this->data['new_password'] === $this->loggedUser->idCard) {
        throw new Error('Contraseña poco segura, por favor utilice números, símbolos y mayúsculas');
      }

      $this->loggedUser->setPassword($this->data['new_password']);
      $this->userRepository->save($this->loggedUser);
      self::setMessage('Contraseña actualizada exitósamente');
      Session::set('mustChangePassword', false);
    } catch (Throwable $error) {
      self::setError($error);
    }

    Flight::redirect('/perfil');
  }
}
