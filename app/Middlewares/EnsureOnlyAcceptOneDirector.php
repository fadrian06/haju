<?php

declare(strict_types=1);

namespace App\Middlewares;

use Flight;
use flight\net\Request;
use Leaf\Http\Session;

final readonly class EnsureOnlyAcceptOneDirector {
  private function __construct(
    private Request $request,
    private Session $session,
  ) {
  }

  public function before(): void {
    if ($this->request->data['secret_key'] !== null) {
      $this->checkSecretKey($this->request->data['secret_key']);
      $this->session->set('let_register_director', true);

      exit(<<<'html'
      <script>
        location.href = './registrate'
      </script>
      html);
    }

    if ($this->session->get('let_register_director', false) !== false) {
      return;
    }

    Flight::redirect('/ingresar');

    exit;
  }

  private function checkSecretKey(string $secretKey): void {
    if ($secretKey !== '1234') {
      renderPage('login', 'Ingreso (1/2)', [
        'error' => 'Clave maestra incorrecta'
      ]);

      exit;
    }
  }
}
