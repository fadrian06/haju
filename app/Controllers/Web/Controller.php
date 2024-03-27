<?php

namespace App\Controllers\Web;

use App;
use Throwable;

abstract class Controller {
  final protected static function setError(Throwable|string $error): void {
    if ($error instanceof Throwable) {
      if (!$_ENV['DEBUG']) {
        $error = $error->getMessage();
      }
    }

    App::session()->set('error', "❌ $error");
  }

  final protected static function setMessage(string $message): void {
    App::session()->set('message', "✔ $message");
  }

  /**
   * @return string Profile image URL path
   */
  final protected static function uploadFile(string $postParam, string $destinationFolder): string {
    $files = App::request()->files;

    $temporalFileAbsPath = $files[$postParam]['tmp_name'];
    $fileName = $files[$postParam]['name'];

    $filePath = [
      'rel' => "assets/img/$destinationFolder/{$fileName}",
      'abs' => dirname(__DIR__, 3) . "/assets/img/$destinationFolder/{$fileName}"
    ];

    copy($temporalFileAbsPath, $filePath['abs']);

    return $filePath['rel'];
  }
}
