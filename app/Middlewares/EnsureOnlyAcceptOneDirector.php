<?php



namespace HAJU\Middlewares;

use Flight;
use Leaf\Http\Session;

final readonly class EnsureOnlyAcceptOneDirector
{
  public function before(): void
  {
    if (Flight::request()->data['secret_key'] !== null) {
      if (Flight::request()->data['secret_key'] !== $_ENV['SECRET_KEY']) {
        renderPage('login', 'Ingreso (1/2)', [
          'error' => 'Clave maestra incorrecta'
        ]);

        return;
      }

      Session::set('let_register_director', true);

      echo <<<'html'
      <script>
        location.href = './registrate'
      </script>
      html;

      return;
    }

    if (Session::get('let_register_director', false) !== false) {
      return;
    }

    Flight::redirect('/ingresar');
  }
}
