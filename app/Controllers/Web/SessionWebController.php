<?php

namespace App\Controllers\Web;

use App;
use App\Repositories\Domain\UserRepository;
use Error;

final class SessionWebController extends Controller {
  private readonly UserRepository $userRepository;
  private const DEFAULT_PASSWORD = '1234';

  function __construct() {
    parent::__construct();

    $this->userRepository = App::userRepository();
  }

  function logOut(): void {
    $this->session->destroy();
    App::redirect('/ingresar');
  }

  function showLogin(): void {
    App::renderPage('login', 'Ingreso (1/2)');
  }

  function handleLogin(): void {
    $user = $this->userRepository->getByIdCard($this->data['id_card']);

    try {
      if (!$this->data['id_card']) {
        throw new Error('La cédula es requerida');
      }

      if (!$this->data['password']) {
        throw new Error('La contraseña es requerida');
      } elseif ($this->data['password'] === self::DEFAULT_PASSWORD) {
        $this->session->set('mustChangePassword', true);
      }

      if (!$user?->checkPassword($this->data['password'])) {
        throw new Error('Cédula o contraseña incorrecta');
      }

      $user->ensureThatIsActive()->ensureHasActiveDepartments();
      $this->session->set('userId', $user->id);

      exit(App::redirect('/departamento/seleccionar'));
    } catch (Error $error) {
      self::setError($error);
    }

    App::redirect('/ingresar');
  }

  function showDepartments(): void {
    $departments = [];

    foreach ($this->loggedUser->getDepartment() as $department) {
      $departments[] = $department;
    }

    if (count($departments) === 1) {
      exit(App::redirect("/departamento/seleccionar/{$departments[0]->id}"));
    }

    App::renderPage('select-department', 'Ingresar (2/2)');
  }

  function saveChoice(int $id): void {
    $this->session->set('departmentId', $id);
    App::redirect('/');
  }
}
