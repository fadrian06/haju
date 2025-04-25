<?php

declare(strict_types=1);

namespace HAJU\Controllers;

use HAJU\Models\User;
use Error;
use Flight;
use flight\util\Collection;
use Leaf\Http\Session;
use Throwable;

abstract readonly class Controller
{
  protected ?User $loggedUser;
  protected Collection $data;

  public function __construct()
  {
    $this->loggedUser = Flight::view()->get('user');
    $this->data = Flight::request()->data;
  }

  final protected static function setError(Throwable|string $error): void
  {
    ini_set('error_log', LOGS_PATH . '/error.log');

    if ($error instanceof Throwable) {
      error_log($error->__toString());
      error_log("\n\n");

      $error = $error->getMessage();
    }

    Session::set('error', $error);
  }

  final protected static function setMessage(string $message): void
  {
    Session::set('message', $message);
  }

  /**
   * @return string File URL relative path.
   * @throws Error If file isn't provided.
   */
  final protected static function ensureThatFileIsSaved(
    string $fileParam,
    string $urlParam,
    string $fileId,
    string $destinationFolder,
    string $errorMessage
  ): string {
    $url = Flight::request()->data[$urlParam];
    $files = Flight::request()->files;
    $fileName = "{$fileId}.jpg";

    if (is_string($url) && $url !== '') {
      $image = file_get_contents($url);

      $filePath = [
        'rel' => "assets/img/{$destinationFolder}/{$fileName}",
        'abs' => ROOT_PATH . "/assets/img/{$destinationFolder}/{$fileName}",
      ];

      file_put_contents($filePath['abs'], $image);

      return $filePath['rel'];
    }

    if ($files[$fileParam]['size'] === 0) {
      throw new Error($errorMessage);
    }

    $temporalFileAbsPath = $files[$fileParam]['tmp_name'];

    $filePath = [
      'rel' => "assets/img/{$destinationFolder}/{$fileName}",
      'abs' => ROOT_PATH . "/assets/img/{$destinationFolder}/{$fileName}",
    ];

    copy($temporalFileAbsPath, $filePath['abs']);

    return $filePath['rel'];
  }
}
