<?php

declare(strict_types=1);

namespace App\Controllers\Web;

use App\Repositories\Domain\UserRepository;
use Error;
use Flight;
use Leaf\Http\Session;
use ZxcvbnPhp\Zxcvbn;

final readonly class SessionWebController extends Controller {
  private const INSECURE_PASSWORD_STRENGTH_LEVEL = 2;

  public function __construct(
    private UserRepository $userRepository,
    private Zxcvbn $passwordValidator,
  ) {
    parent::__construct();
  }

  public function logOut(): void {
    static $excludedKeys = ['error', 'message'];

    foreach (array_keys(Session::all()) as $key) {
      if (in_array($key, $excludedKeys, true)) {
        continue;
      }

      Session::unset($key);
    }

    Flight::redirect('/ingresar');
  }

  public function showLogin(): void {
    renderPage('login', 'Ingreso (1/2)');
  }

  public function handleLogin(): void {
    $user = $this->userRepository->getByIdCard((int) $this->data['id_card']);

    try {
      if ($this->data['id_card'] === null) {
        throw new Error('La cédula es requerida');
      }
      if ($this->data['password'] === null) {
        throw new Error('La contraseña es requerida');
      }

      if (!$user?->checkPassword($this->data['password'])) {
        throw new Error('Cédula o contraseña incorrecta');
      }

      ['score' => $passwordStrengthLevel] = $this
        ->passwordValidator
        ->passwordStrength($this->data['password']);

      if (
        $passwordStrengthLevel <= self::INSECURE_PASSWORD_STRENGTH_LEVEL
        || $this->data['password'] === $this->data['id_card']
      ) {
        Session::set('mustChangePassword', true);
      }

      $user->ensureThatIsActive()->ensureHasActiveDepartments();
      Session::set('userId', $user->id);
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
    Session::set('departmentId', $id);
    Flight::redirect('/');
  }
}
