<?php

namespace App\Middlewares;

use App;

class EnsureOnlyAcceptOneDirector {
  static function before(): void {
    if (App::request()->data['secret_key']) {
      self::checkSecretKey(App::request()->data['secret_key']);
      App::session()->set('let_register_director', true);

      exit(<<<html
      <script>
        location.href = './registrate'
      </script>
      html);
    }

    if (App::session()->get('let_register_director')) {
      return;
    }

    exit(App::redirect('/ingresar'));
  }

  private static function checkSecretKey(string $secretKey): void {
    if ($secretKey !== '1234') {
      exit(App::renderPage('login', 'Ingreso (1/2)', [
        'error' => 'Clave maestra incorrecta'
      ]));
    }
  }
}