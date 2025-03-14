<?php

declare(strict_types=1);

namespace App\Controllers\Web;

use App;
use App\Repositories\Domain\UserRepository;
use Error;
use Leaf\Http\Session;

final class SessionWebController extends Controller {
  private readonly UserRepository $userRepository;
  private const DEFAULT_PASSWORD = '1234';

  function __construct() {
    parent::__construct();

    $this->userRepository = App::userRepository();
  }

  function logOut(): void {
    Session::unset('userId');
    Session::unset('department');
    App::redirect('/ingresar');
  }

  function showLogin(): void {
    App::renderPage('login', 'Ingreso (1/2)');
  }

  function handleLogin(): void {
    $user = $this->userRepository->getByIdCard((int) $this->data['id_card']);

    try {
      if (!$this->data['id_card']) {
        throw new Error('La cédula es requerida');
      }

      if (!$this->data['password']) {
        throw new Error('La contraseña es requerida');
      } elseif ($this->data['password'] === self::DEFAULT_PASSWORD) {
        Session::set('mustChangePassword', true);
      }

      if (!$user?->checkPassword($this->data['password'])) {
        throw new Error('Cédula o contraseña incorrecta');
      }

      $user->ensureThatIsActive()->ensureHasActiveDepartments();
      Session::set('userId', $user->id);
      App::redirect('/departamento/seleccionar');
    } catch (Error $error) {
      self::setError($error);
      App::redirect('/ingresar');
    }
  }

  function showDepartments(): void {
    $departments = [];

    foreach ($this->loggedUser->getDepartment() as $department) {
      $departments[] = $department;
    }

    if (count($departments) === 1) {
      App::redirect("/departamento/seleccionar/{$departments[0]->id}");

      return;
    }

    App::renderPage('select-department', 'Ingresar (2/2)');
  }

  function saveChoice(int $id): void {
    $this->session->set('departmentId', $id);
    App::redirect('/');
  }
}
