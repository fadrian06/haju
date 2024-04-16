<?php

use App\Repositories\Domain\ConsultationCauseCategoryRepository;
use App\Repositories\Domain\ConsultationCauseRepository;
use App\Repositories\Domain\DepartmentRepository;
use App\Repositories\Domain\PatientRepository;
use App\Repositories\Domain\SettingsRepository;
use App\Repositories\Domain\UserRepository;
use App\Repositories\Infraestructure\PDO\Connection;
use flight\net\Route;
use Leaf\Http\Session;

/**
 * @method static UserRepository userRepository()
 * @method static DepartmentRepository departmentRepository()
 * @method static SettingsRepository settingsRepository()
 * @method static Session session()
 * @method static Connection db()
 * @method static PatientRepository patientRepository()
 * @method static ConsultationCauseCategoryRepository consultationCauseCategoryRepository()
 * @method static ConsultationCauseRepository consultationCauseRepository()
 */
class App extends Flight {
  static function route(
    string $pattern,
    $callback,
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
