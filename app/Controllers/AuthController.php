<?php

declare(strict_types=1);

namespace HAJU\Controllers;

use App\Models\User;
use Flight;
use Leaf\Flash;
use Leaf\Http\Session;
use ZxcvbnPhp\Zxcvbn;

final readonly class AuthController {
  private const INSECURE_PASSWORD_STRENGTH_LEVEL = 2;

  private function __construct() {
  }

  public static function logout(): void {
    auth()->logout();

    Flight::redirect('/ingresar');
  }

  public static function showLogin(): void {
    renderPage('login', 'Ingreso (1/2)');
  }

  public static function handleLogin(): void {
    $credentials = form()->validate(Flight::request()->data->getData(), [
      'id_card' => 'min:1',
      'password' => 'min:1',
    ]);

    if (!$credentials) {
      Flash::set('La cédula y contraseña son requeridas', 'error');
      Flight::redirect('/ingresar');

      return;
    }

    $rawPassword = $credentials['password'];
    $zxcvbn = new Zxcvbn;
    $passwordStrength = $zxcvbn->passwordStrength($rawPassword);

    if (
      $passwordStrength['score'] <= self::INSECURE_PASSWORD_STRENGTH_LEVEL
      || $rawPassword === $credentials['id_card']
    ) {
      Session::set('mustChangePassword', true);
    }

    $wasLoggedSuccessfully = auth()->login($credentials);

    if (!$wasLoggedSuccessfully) {
      Flash::set('Cédula o contraseña incorrecta', 'error');
      Flight::redirect('/ingresar');

      return;
    }

    $loggedUser = User::query()->findOrFail(auth()->id());
    $loggedUser->ensureThatIsActive()->ensureHasActiveDepartments();

    Flight::redirect('/departamento/seleccionar');
  }
}
