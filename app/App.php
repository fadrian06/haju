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
use Leaf\Http\Session;

/** @deprecated */
final class App extends Flight {
  /** @deprecated */
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
    return container()->get(Connection::class);
  }

  /** @deprecated */
  public static function departmentRepository(): ?DepartmentRepository {
    return container()->get(DepartmentRepository::class);
  }

  /** @deprecated */
  public static function userRepository(): ?UserRepository {
    return container()->get(UserRepository::class);
  }

  /** @deprecated */
  public static function settingsRepository(): ?SettingsRepository {
    return container()->get(SettingsRepository::class);
  }

  /** @deprecated */
  public static function consultationCauseCategoryRepository(): ?ConsultationCauseCategoryRepository {
    return container()->get(ConsultationCauseCategoryRepository::class);
  }

  /** @deprecated */
  public static function session(): ?Session {
    return container()->get(Session::class);
  }

  /** @deprecated */
  public static function patientRepository(): ?PatientRepository {
    return container()->get(PatientRepository::class);
  }

  /** @deprecated */
  public static function consultationCauseRepository(): ?ConsultationCauseRepository {
    return container()->get(ConsultationCauseRepository::class);
  }

  /** @deprecated */
  public static function doctorRepository(): ?DoctorRepository {
    return container()->get(DoctorRepository::class);
  }
}
