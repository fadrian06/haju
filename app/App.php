<?php

use flight\net\Route;

class App extends Flight {
  static function route(string $pattern, callable $callback, bool $pass_route = false, string $alias = ''): Route {
    return self::router()->map($pattern, $callback, $pass_route, $pattern);
  }
}
