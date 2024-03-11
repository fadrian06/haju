<?php

namespace App\Controllers\Web;

use App;

abstract class Controller {
  final protected static function setError(string $error): void {
    App::session()->set('error', "❌ $error");
  }

  final protected static function setMessage(string $message): void {
    App::session()->set('message', "✔ $message");
  }
}
