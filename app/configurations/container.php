<?php

declare(strict_types=1);

use App\Enums\DBDriver;
use App\Repositories\Domain\ConsultationCauseCategoryRepository;
use App\Repositories\Domain\ConsultationCauseRepository;
use App\Repositories\Domain\DepartmentRepository;
use App\Repositories\Domain\DoctorRepository;
use App\Repositories\Domain\PatientRepository;
use App\Repositories\Domain\SettingsRepository;
use App\Repositories\Domain\UserRepository;
use App\Repositories\Infraestructure\Files\FilesSettingsRepository;
use App\Repositories\Infraestructure\PDO\PDOConsultationCauseCategoryRepository;
use App\Repositories\Infraestructure\PDO\PDOConsultationCauseRepository;
use App\Repositories\Infraestructure\PDO\PDODepartmentRepository;
use App\Repositories\Infraestructure\PDO\PDODoctorRepository;
use App\Repositories\Infraestructure\PDO\PDOPatientRepository;
use App\Repositories\Infraestructure\PDO\PDOUserRepository;
use flight\Container;
use flight\net\Request;
use flight\template\View;
use Leaf\Http\Session;
use Psr\Container\ContainerInterface;

$container = Container::getInstance();
$container->singleton(ContainerInterface::class, Container::class);
$container->singleton(Session::class);
$container->singleton(View::class, Flight::view());
$container->singleton(Request::class, Flight::request());

assert($_ENV['DB_CONNECTION'] instanceof DBDriver);

$container->singleton(PDO::class, static fn(): PDO => new PDO(
  match (strtolower($_ENV['DB_CONNECTION']->value)) {
    'sqlite' => 'sqlite:'
      . __DIR__
      . '/../../database/'
      . ($_ENV['DB_DATABASE'] ?? 'haju')
      . '.db',
    'mysql' => 'mysql:host='
      . $_ENV['DB_HOST']
      . ';dbname='
      . $_ENV['DB_DATABASE']
      . ';port='
      . $_ENV['DB_PORT'],
  },
  $_ENV['DB_USERNAME'] ?? null,
  $_ENV['DB_PASSWORD'] ?? null,
));

$container->singleton(
  DepartmentRepository::class,
  static fn(): DepartmentRepository => new PDODepartmentRepository(
    $container->get(PDO::class),
    BASE_URL
  )
);

$container->singleton(
  UserRepository::class,
  static fn(): UserRepository => new PDOUserRepository(
    $container->get(PDO::class),
    BASE_URL,
    $container->get(DepartmentRepository::class)
  )
);

$container->singleton(
  SettingsRepository::class,
  static fn(): SettingsRepository => new FilesSettingsRepository(
    $container->get(PDO::class)
  )
);

$container->singleton(
  ConsultationCauseCategoryRepository::class,
  // phpcs:ignore Generic.Files.LineLength.TooLong
  static fn(): ConsultationCauseCategoryRepository => new PDOConsultationCauseCategoryRepository(
    Container::getInstance()->get(PDO::class),
    BASE_URL
  )
);

$container->singleton(
  ConsultationCauseRepository::class,
  // phpcs:ignore Generic.Files.LineLength.TooLong
  static fn(): ConsultationCauseRepository => new PDOConsultationCauseRepository(
    Container::getInstance()->get(PDO::class),
    BASE_URL,
    Container::getInstance()->get(ConsultationCauseCategoryRepository::class)
  )
);

$container->singleton(
  DoctorRepository::class,
  static fn(): DoctorRepository => new PDODoctorRepository(
    $container->get(PDO::class),
    BASE_URL,
    $container->get(UserRepository::class)
  )
);

$container->singleton(
  PatientRepository::class,
  static fn(): PatientRepository => new PDOPatientRepository(
    $container->get(PDO::class),
    BASE_URL,
    $container->get(UserRepository::class),
    $container->get(ConsultationCauseRepository::class),
    $container->get(DepartmentRepository::class),
    $container->get(DoctorRepository::class)
  )
);

Flight::registerContainerHandler($container);
