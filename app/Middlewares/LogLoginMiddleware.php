<?php



namespace HAJU\Middlewares;

use Flight;
use HAJU\Models\User;

final readonly class LogLoginMiddleware
{
  private const LOG_FILE_PATH = LOGS_PATH . '/authentications.log';

  public function __construct()
  {
    if (!file_exists(self::LOG_FILE_PATH)) {
      file_put_contents(self::LOG_FILE_PATH, '');
    }
  }

  public function after(): void
  {
    $loggedUser = Flight::view()->get('user');
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
