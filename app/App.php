<?php

use App\Repositories\Domain\DepartmentRepository;
use App\Repositories\Domain\SettingsRepository;
use App\Repositories\Domain\UserRepository;
use flight\net\Route;
use Leaf\Http\Session;

/**
 * @method static UserRepository userRepository()
 * @method static DepartmentRepository departmentRepository()
 * @method static SettingsRepository settingsRepository()
 * @method static Session session()
 */
class App extends Flight {
  static function route(
    string $pattern,
    callable $callback,
    bool $pass_route = false,
    string $alias = ''
  ): Route {
    $alias = substr($pattern, strpos($pattern, '/'));

    return self::router()->map($pattern, $callback, $pass_route, $alias);
  }

  static function renderPage(
    string $page,
    string $title,
    array $params = [],
    string $layout = 'base'
  ): void {
    App::render("pages/$page", $params, 'content');
    App::render("layouts/$layout", compact('title'));
  }
}
