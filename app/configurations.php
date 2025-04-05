<?php

declare(strict_types=1);

use App\Repositories\Domain\ConsultationCauseCategoryRepository;
use App\Repositories\Domain\ConsultationCauseRepository;
use App\Repositories\Domain\DepartmentRepository;
use App\Repositories\Domain\DoctorRepository;
use App\Repositories\Domain\PatientRepository;
use App\Repositories\Domain\SettingsRepository;
use App\Repositories\Domain\UserRepository;
use App\Repositories\Infraestructure\Files\FilesSettingsRepository;
use App\Repositories\Infraestructure\PDO\Connection;
use App\Repositories\Infraestructure\PDO\PDOConsultationCauseCategoryRepository;
use App\Repositories\Infraestructure\PDO\PDOConsultationCauseRepository;
use App\Repositories\Infraestructure\PDO\PDODepartmentRepository;
use App\Repositories\Infraestructure\PDO\PDODoctorRepository;
use App\Repositories\Infraestructure\PDO\PDOPatientRepository;
use App\Repositories\Infraestructure\PDO\PDOUserRepository;
use Illuminate\Container\Container;

error_reporting(E_ALL & E_STRICT);

$_ENV += include __DIR__ . '/../.env.php';
$_ENV += include __DIR__ . '/../.env.dist.php';

date_default_timezone_set($_ENV['TIMEZONE']);

App::set('root', str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']));

App::set(
  'fullRoot',
  App::request()->scheme . '://' . App::request()->host . App::get('root')
);

App::set('flight.views.path', 'views');
App::view()->set('root', App::get('root'));
App::view()->set('user', null);
App::view()->preserveVars = false;

$container = new class extends Container {
  public function terminating(): void {
  }

  public function getNamespace(): void {
  }
};

$container->singleton(
  Connection::class,
  static fn(): Connection => new Connection(
    $_ENV['DB_CONNECTION'],
    $_ENV['DB_DATABASE'],
    $_ENV['DB_HOST'],
    $_ENV['DB_PORT'],
    $_ENV['DB_USERNAME'],
    $_ENV['DB_PASSWORD']
  )
);

$container->singleton(
  DepartmentRepository::class,
  static fn(): DepartmentRepository => new PDODepartmentRepository(
    $container->get(Connection::class),
    App::get('fullRoot')
  )
);

$container->singleton(
  UserRepository::class,
  static fn(): UserRepository => new PDOUserRepository(
    $container->get(Connection::class),
    App::get('fullRoot'),
    $container->get(DepartmentRepository::class)
  )
);

$container->singleton(
  SettingsRepository::class,
  static fn(): SettingsRepository => new FilesSettingsRepository(
    $container->get(Connection::class)
  )
);

$container->singleton(
  ConsultationCauseCategoryRepository::class,
  static fn(): ConsultationCauseCategoryRepository => new PDOConsultationCauseCategoryRepository(
    $container->get(Connection::class),
    App::get('fullRoot')
  )
);

$container->singleton(
  ConsultationCauseRepository::class,
  static fn(): ConsultationCauseRepository => new PDOConsultationCauseRepository(
    $container->get(Connection::class),
    App::get('fullRoot'),
    $container->get(ConsultationCauseCategoryRepository::class)
  )
);

$container->singleton(
  DoctorRepository::class,
  static fn(): DoctorRepository => new PDODoctorRepository(
    $container->get(Connection::class),
    App::get('fullRoot'),
    $container->get(UserRepository::class)
  )
);

$container->singleton(
  PatientRepository::class,
  static fn(): PatientRepository => new PDOPatientRepository(
    $container->get(Connection::class),
    App::get('fullRoot'),
    $container->get(UserRepository::class),
    $container->get(ConsultationCauseRepository::class),
    $container->get(DepartmentRepository::class),
    $container->get(DoctorRepository::class)
  )
);

Container::setInstance($container);
App::registerContainerHandler($container);
