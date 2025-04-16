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
use App\Repositories\Infraestructure\PDO\PDOConsultationCauseCategoryRepository;
use App\Repositories\Infraestructure\PDO\PDOConsultationCauseRepository;
use App\Repositories\Infraestructure\PDO\PDODepartmentRepository;
use App\Repositories\Infraestructure\PDO\PDODoctorRepository;
use App\Repositories\Infraestructure\PDO\PDOPatientRepository;
use App\Repositories\Infraestructure\PDO\PDOUserRepository;
use Illuminate\Container\Container;

Container::getInstance()->singleton(
  DepartmentRepository::class,
  static fn(): DepartmentRepository => new PDODepartmentRepository(
    Container::getInstance()->get(PDO::class),
    BASE_URL
  )
);

Container::getInstance()->singleton(
  UserRepository::class,
  static fn(): UserRepository => new PDOUserRepository(
    Container::getInstance()->get(PDO::class),
    BASE_URL,
    Container::getInstance()->get(DepartmentRepository::class)
  )
);

Container::getInstance()->singleton(
  SettingsRepository::class,
  static fn(): SettingsRepository => new FilesSettingsRepository(
    Container::getInstance()->get(PDO::class)
  )
);

Container::getInstance()->singleton(
  ConsultationCauseCategoryRepository::class,
  // phpcs:ignore Generic.Files.LineLength.TooLong
  static fn(): ConsultationCauseCategoryRepository => new PDOConsultationCauseCategoryRepository(
    Container::getInstance()->get(PDO::class),
    BASE_URL
  )
);

Container::getInstance()->singleton(
  ConsultationCauseRepository::class,
  // phpcs:ignore Generic.Files.LineLength.TooLong
  static fn(): ConsultationCauseRepository => new PDOConsultationCauseRepository(
    Container::getInstance()->get(PDO::class),
    BASE_URL,
    Container::getInstance()->get(ConsultationCauseCategoryRepository::class)
  )
);

Container::getInstance()->singleton(
  DoctorRepository::class,
  static fn(): DoctorRepository => new PDODoctorRepository(
    Container::getInstance()->get(PDO::class),
    BASE_URL,
    Container::getInstance()->get(UserRepository::class)
  )
);

Container::getInstance()->singleton(
  PatientRepository::class,
  static fn(): PatientRepository => new PDOPatientRepository(
    Container::getInstance()->get(PDO::class),
    BASE_URL,
    Container::getInstance()->get(UserRepository::class),
    Container::getInstance()->get(ConsultationCauseRepository::class),
    Container::getInstance()->get(DepartmentRepository::class),
    Container::getInstance()->get(DoctorRepository::class)
  )
);
