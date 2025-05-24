<?php

declare(strict_types=1);

use HAJU\Enums\DBDriver;
use HAJU\Repositories\Domain\ConsultationCauseCategoryRepository;
use HAJU\Repositories\Domain\ConsultationCauseRepository;
use HAJU\Repositories\Domain\DepartmentRepository;
use HAJU\Repositories\Domain\DoctorRepository;
use HAJU\Repositories\Domain\PatientRepository;
use HAJU\Repositories\Domain\SettingsRepository;
use HAJU\Repositories\Domain\UserRepository;
use HAJU\Repositories\Infraestructure\Files\FilesSettingsRepository;
use HAJU\Repositories\Infraestructure\PDO\PDOConsultationCauseCategoryRepository;
use HAJU\Repositories\Infraestructure\PDO\PDOConsultationCauseRepository;
use HAJU\Repositories\Infraestructure\PDO\PDODepartmentRepository;
use HAJU\Repositories\Infraestructure\PDO\PDODoctorRepository;
use HAJU\Repositories\Infraestructure\PDO\PDOPatientRepository;
use HAJU\Repositories\Infraestructure\PDO\PDOUserRepository;
use flight\Container;
use HAJU\InstructionLevels\Application\InstructionLevelSearcher;
use HAJU\InstructionLevels\Domain\InstructionLevelRepository;
use HAJU\InstructionLevels\Infrastructure\SqliteInstructionLevelRepository;

$container = Container::getInstance();

assert($_ENV['DB_CONNECTION'] instanceof DBDriver);

$container->singleton(PDO::class, static fn(): PDO => new PDO(
  $_ENV['DB_CONNECTION']->getPdoDsn(),
  $_ENV['DB_USERNAME'],
  $_ENV['DB_PASSWORD'],
));

$container->singleton(SQLite3::class, static fn(): SQLite3 => new SQLite3(
  $_ENV['DB_DATABASE'],
));

$container->singleton(
  InstructionLevelRepository::class,
  SqliteInstructionLevelRepository::class
);

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
    $container->get(DepartmentRepository::class),
    $container->get(InstructionLevelSearcher::class),
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
  static fn(): ConsultationCauseCategoryRepository => new PDOConsultationCauseCategoryRepository(
    $container->get(PDO::class),
    BASE_URL
  )
);

$container->singleton(
  ConsultationCauseRepository::class,
  static fn(): ConsultationCauseRepository => new PDOConsultationCauseRepository(
    $container->get(PDO::class),
    BASE_URL,
    $container->get(ConsultationCauseCategoryRepository::class)
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
