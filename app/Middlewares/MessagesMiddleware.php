<?php

declare(strict_types=1);

namespace App\Middlewares;

use flight\template\View;
use Leaf\Http\Session;

final readonly class MessagesMiddleware {
  public function __construct(private Session $session, private View $view) {
  }

  public function before(): void {
    $this->view->set('error', $this->session->retrieve('error', null, true));

    $this->view->set(
      'message',
      $this->session->retrieve('message', null, true)
    );

    $this->view->set(
      'scriptPath',
      $this->session->get('scriptPath', null, true)
    );

    $this->view->set(
      'mustChangePassword',
      $this->session->get('mustChangePassword', false)
    );
  }
}
