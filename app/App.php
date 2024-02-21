<?php

use App\Repositories\Domain\UserRepository;
use flight\net\Route;

/**
 * @method static UserRepository userRepository()
 */
class App extends Flight {
  static function route(string $pattern, callable $callback, bool $pass_route = false, string $alias = ''): Route {
    $alias = substr($pattern, strpos($pattern, '/'));

    return self::router()->map($pattern, $callback, $pass_route, $alias);
  }
}
