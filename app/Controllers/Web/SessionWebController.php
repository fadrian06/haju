<?php

declare(strict_types=1);

namespace App\Controllers\Web;

use App\Repositories\Domain\UserRepository;
use Error;
use Flight;
use ZxcvbnPhp\Zxcvbn;

final class SessionWebController extends Controller {
  private const INSECURE_PASSWORD_STRENGTH_LEVEL = 2;

  public function __construct(
    private readonly UserRepository $userRepository,
    private readonly Zxcvbn $passwordValidator,
  ) {
    parent::__construct();
  }

  public function logOut(): void {
    $this->session->unset('userId');
    $this->session->unset('department');
    Flight::redirect('/ingresar');
  }

  public function showLogin(): void {
    renderPage('login', 'Ingreso (1/2)');
  }

  public function handleLogin(): void {
    $user = $this->userRepository->getByIdCard((int) $this->data['id_card']);

    try {
      if (!$this->data['id_card']) {
        throw new Error('La cédula es requerida');
      }
      if (!$this->data['password']) {
        throw new Error('La contraseña es requerida');
      }

      if (!$user?->checkPassword($this->data['password'])) {
        throw new Error('Cédula o contraseña incorrecta');
      }

      $passwordStrength = $this->passwordValidator->passwordStrength($this->data['password']);

      if (
        $passwordStrength['score'] <= self::INSECURE_PASSWORD_STRENGTH_LEVEL
        || $this->data['password'] === $this->data['id_card']
      ) {
        $this->session->set('mustChangePassword', true);
      }

      $user->ensureThatIsActive()->ensureHasActiveDepartments();
      $this->session->set('userId', $user->id);
      Flight::redirect('/departamento/seleccionar');
    } catch (Error $error) {
      self::setError($error);
      Flight::redirect('/ingresar');
    }
  }

  public function showDepartments(): void {
    $departments = [];

    foreach ($this->loggedUser->getDepartment() as $department) {
      $departments[] = $department;
    }

    if (count($departments) === 1) {
      Flight::redirect("/departamento/seleccionar/{$departments[0]->id}");

      return;
    }

    renderPage('select-department', 'Ingresar (2/2)');
  }

  public function saveChoice(int $id): void {
    $this->session->set('departmentId', $id);
    Flight::redirect('/');
  }
}
