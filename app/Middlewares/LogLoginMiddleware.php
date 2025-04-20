<?php

declare(strict_types=1);

namespace App\Middlewares;

use App\OldModels\User;
use flight\template\View;

final readonly class LogLoginMiddleware {
  private const LOG_FILE_PATH = __DIR__ . '/../logs/authentications.log';

  public function __construct(private View $view) {
    if (!file_exists(self::LOG_FILE_PATH)) {
      file_put_contents(self::LOG_FILE_PATH, '');
    }
  }

  public function after(): void {
    $loggedUser = $this->view->get('user');
    assert($loggedUser instanceof User);

    if ($loggedUser) {
      $data = (
        "El usuario {$loggedUser->getFullName()} se ha autenticado el "
        . date('d/m/Y H:i:s')
        . PHP_EOL
        . ';'
      );

      file_put_contents(self::LOG_FILE_PATH, $data, FILE_APPEND);
    }
  }
}
