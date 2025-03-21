<?php

declare(strict_types=1);

use App\Repositories\Domain\ConsultationCauseCategoryRepository;
use App\Repositories\Domain\ConsultationCauseRepository;
use App\Repositories\Domain\DepartmentRepository;
use App\Repositories\Domain\DoctorRepository;
use App\Repositories\Domain\PatientRepository;
use App\Repositories\Domain\SettingsRepository;
use App\Repositories\Domain\UserRepository;
use App\Repositories\Infraestructure\PDO\Connection;
use flight\net\Route;
use Illuminate\Container\Container;
use Leaf\Http\Session;

final class App extends Flight {
  public static function route(
    string $pattern,
    $callback,
    bool $pass_route = false,
    string $alias = ''
  ): Route {
    $alias = substr($pattern, strpos($pattern, '/'));

    return self::router()->map($pattern, $callback, $pass_route, $alias);
  }

  public static function renderPage(
    string $page,
    string $title,
    array $params = [],
    string $layout = 'base'
  ): void {
    self::render("pages/{$page}", $params, 'content');
    self::render("layouts/{$layout}", compact('title') + $params);
  }

  /** @deprecated */
  public static function db(): ?Connection {
    return Container::getInstance()->get(Connection::class);
  }

  /** @deprecated */
  public static function departmentRepository(): ?DepartmentRepository {
    return Container::getInstance()->get(DepartmentRepository::class);
  }

  /** @deprecated */
  public static function userRepository(): ?UserRepository {
    return Container::getInstance()->get(UserRepository::class);
  }

  /** @deprecated */
  public static function settingsRepository(): ?SettingsRepository {
    return Container::getInstance()->get(SettingsRepository::class);
  }

  /** @deprecated */
  public static function consultationCauseCategoryRepository(): ?ConsultationCauseCategoryRepository {
    return Container::getInstance()->get(ConsultationCauseCategoryRepository::class);
  }

  /** @deprecated */
  public static function session(): ?Session {
    return Container::getInstance()->get(Session::class);
  }

  /** @deprecated */
  public static function patientRepository(): ?PatientRepository {
    return Container::getInstance()->get(PatientRepository::class);
  }

  /** @deprecated */
  public static function consultationCauseRepository(): ?ConsultationCauseRepository {
    return Container::getInstance()->get(ConsultationCauseRepository::class);
  }

  /** @deprecated */
  public static function doctorRepository(): ?DoctorRepository {
    return Container::getInstance()->get(DoctorRepository::class);
  }
}
