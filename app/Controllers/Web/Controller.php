<?php

declare(strict_types=1);

namespace App\Controllers\Web;

use App;
use App\Models\User;
use Error;
use flight\util\Collection;
use Leaf\Http\Session;
use Throwable;

abstract class Controller {
  protected readonly ?User $loggedUser;
  protected readonly Collection $data;
  protected readonly Session $session;

  public function __construct() {
    $this->loggedUser = App::view()->get('user');
    $this->data = App::request()->data;
    $this->session = App::session();
  }

  final protected static function setError(Throwable|string $error): void {
    if ($error instanceof Throwable) {
      $error = $error->getMessage();
      error_log($error);
      error_log("\n\n");
    }

    App::session()->set('error', $error);
  }

  final protected static function setMessage(string $message): void {
    App::session()->set('message', $message);
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
    $url = App::request()->data[$urlParam];
    $files = App::request()->files;
    $fileName = "{$fileId}.jpg";

    if (is_string($url) && $url !== '') {
      $image = file_get_contents($url);

      $filePath = [
        'rel' => "assets/img/{$destinationFolder}/{$fileName}",
        'abs' => dirname(__DIR__, 3) . "/assets/img/{$destinationFolder}/{$fileName}"
      ];

      file_put_contents($filePath['abs'], $image);

      return $filePath['rel'];
    }

    if (!$files[$fileParam]['size']) {
      throw new Error($errorMessage);
    }

    $temporalFileAbsPath = $files[$fileParam]['tmp_name'];

    $filePath = [
      'rel' => "assets/img/{$destinationFolder}/{$fileName}",
      'abs' => dirname(__DIR__, 3) . "/assets/img/{$destinationFolder}/{$fileName}"
    ];

    copy($temporalFileAbsPath, $filePath['abs']);

    return $filePath['rel'];
  }
}
