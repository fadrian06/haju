<?php

declare(strict_types=1);

namespace App\Middlewares;

use App;
use App\Models\User;

final readonly class LogLoginMiddleware {
  private const LOG_FILE_PATH = __DIR__ . '/../logs/authentications.log';

  public function __construct() {
    if (!file_exists(self::LOG_FILE_PATH)) {
      file_put_contents(self::LOG_FILE_PATH, '');
    }
  }

  public static function after(): void {
    $loggedUser = App::view()->get('user');
    assert($loggedUser instanceof User);

    if ($loggedUser) {
      file_put_contents(
        self::LOG_FILE_PATH,
        "El usuario {$loggedUser->getFullName()} se ha autenticado el " . date('d/m/Y H:i:s' . PHP_EOL . ';'),
        FILE_APPEND
      );
    }
  }
}
