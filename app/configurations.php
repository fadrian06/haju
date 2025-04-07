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
use flight\template\View;
use Leaf\Http\Session;

error_reporting(E_ALL | E_STRICT);

$_ENV += include __DIR__ . '/../.env.php';
$_ENV += include __DIR__ . '/../.env.dist.php';

date_default_timezone_set($_ENV['TIMEZONE']);

/**
 * - `''`: with _composer serve_ -> _localhost:61001_
 * - `'/haju'`: with xampp -> _localhost/haju_
 * - `'/faslatam.42web.io/htdocs/haju'`: hosting uri
 */
define('BASE_URI', str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']));

/** `http://localhost:61001` */
define(
  'BASE_URL',
  Flight::request()->scheme . '://' . $_SERVER['HTTP_HOST'] . BASE_URI
);

Flight::set('flight.views.path', dirname(__DIR__) . '/views');
Flight::set('flight.handle_errors', false);
Flight::view()->path = dirname(__DIR__) . '/views';
Flight::view()->preserveVars = false;

$container = container();
$container->singleton(Session::class);
$container->singleton(View::class, Flight::view());

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
    BASE_URL
  )
);

$container->singleton(
  UserRepository::class,
  static fn(): UserRepository => new PDOUserRepository(
    $container->get(Connection::class),
    BASE_URL,
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
    BASE_URL
  )
);

$container->singleton(
  ConsultationCauseRepository::class,
  static fn(): ConsultationCauseRepository => new PDOConsultationCauseRepository(
    $container->get(Connection::class),
    BASE_URL,
    $container->get(ConsultationCauseCategoryRepository::class)
  )
);

$container->singleton(
  DoctorRepository::class,
  static fn(): DoctorRepository => new PDODoctorRepository(
    $container->get(Connection::class),
    BASE_URL,
    $container->get(UserRepository::class)
  )
);

$container->singleton(
  PatientRepository::class,
  static fn(): PatientRepository => new PDOPatientRepository(
    $container->get(Connection::class),
    BASE_URL,
    $container->get(UserRepository::class),
    $container->get(ConsultationCauseRepository::class),
    $container->get(DepartmentRepository::class),
    $container->get(DoctorRepository::class)
  )
);

Flight::registerContainerHandler($container);
