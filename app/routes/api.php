<?php

declare(strict_types=1);

use App\Models\Consultation;
use App\Models\ConsultationCause;
use App\Models\ConsultationCauseCategory;
use App\Models\Patient;
use App\Repositories\Domain\ConsultationCauseCategoryRepository;
use App\Repositories\Domain\ConsultationCauseRepository;
use App\Repositories\Domain\PatientRepository;
use Illuminate\Container\Container;
use Leaf\Http\Session;

Flight::route(
  'GET /api/status',
  static fn() => Flight::json(['status' => 'ok'])
);

App::route(
  '/api/preferencias/tema/@theme',
  static function (string $theme): void {
    Session::set('theme', $theme);
  }
);

App::route('/api/verificar-clave-maestra', static function (): void {
  $secretKey = App::request()->data['secret_key'];

  if ($secretKey !== '1234') {
    App::json('Clave maestra incorrecta', 401);
  } else {
    Session::set('let_register_director', true);
  }
});

App::group('/api', function (): void {
  $categoryMapper = new class {
    /**
     * @return array{
     *   id: int,
     *   name: array{
     *     short: string,
     *     extended: string,
     *   },
     *   parentCategory: ?array{
     *     id: int,
     *     name: array{
     *       short: string,
     *       extended: string,
     *     },
     *     parentCategory: null,
     *   }
     * }
     */
    public function __invoke(ConsultationCauseCategory $category): array {
      return [
        'id' => $category->id,
        'name' => [
          'short' => $category->shortName,
          'extended' => $category->extendedName,
        ],
        'parentCategory' => $category->parentCategory !== null
          ? ($this)($category->parentCategory)
          : null,
      ];
    }
  };

  $causeMapper = new class($categoryMapper(...)) {
    public function __construct(private readonly Closure $categoryMapper) {
    }

    /**
     * @return array{
     *   id: int,
     *   name: array{
     *     short: string,
     *     extended: string,
     *   },
     *   code: string,
     *   category: array{
     *     id: int,
     *     name: array{
     *       short: string,
     *       extended: string,
     *     },
     *     parentCategory: ?array{
     *       id: int,
     *       name: array{
     *         short: string,
     *         extended: string,
     *       },
     *       parentCategory: null,
     *     },
     *   },
     * }
     */
    public function __invoke(ConsultationCause $cause): array {
      return [
        'id' => $cause->id,
        'name' => [
          'short' => $cause->getFullName(),
          'extended' => $cause->getFullName(abbreviated: false),
        ],
        'code' => $cause->code,
        'category' => ($this->categoryMapper)($cause->category),
      ];
    }
  };

  $consultationMapper = new class($causeMapper(...)) {
    public function __construct(private readonly Closure $causeMapper) {
    }

    /**
     * @return array{
     *   id: int,
     *   registeredDate: string,
     *   type: array{
     *     id: int,
     *     description: string,
     *   },
     *   department: array{
     *     id: int,
     *     name: string,
     *     registeredDate: string,
     *     icon: string,
     *     belongsToExternalConsultation: bool,
     *     isActive: bool,
     *   },
     *   cause: array{
     *     id: int,
     *     name: array{
     *       short: string,
     *       extended: string,
     *     },
     *     code: string,
     *     category: array{
     *       id: int,
     *       name: array{
     *         short: string,
     *         extended: string,
     *       },
     *       parentCategory: ?array{
     *         id: int,
     *         name: array{
     *           short: string,
     *           extended: string,
     *         },
     *         parentCategory: null,
     *       },
     *     },
     *   },
     * }
     */
    public function __invoke(Consultation $consultation): array {
      $iconFilePath = $consultation->department->iconFilePath;

      return [
        'id' => $consultation->id,
        'registeredDate' => $consultation->registeredDate,
        'type' => [
          'id' => $consultation->type->value,
          'description' => $consultation->type->getDescription(),
        ],
        'department' => [
          'id' => $consultation->department->id,
          'name' => $consultation->department->name,
          'registeredDate' => $consultation->department->registeredDate,
          'icon' => urldecode(
            is_string($iconFilePath)
              ? $iconFilePath
              : $iconFilePath->asString()
          ),
          'belongsToExternalConsultation' => $consultation
            ->department
            ->belongsToExternalConsultation,
          'isActive' => $consultation->department->isActive(),
        ],
        'cause' => ($this->causeMapper)($consultation->cause),
      ];
    }
  };

  $patientMapper = new class {
    /**
     * @return array{
     *   id: int,
     *   names: array{
     *     first: string,
     *     second: string,
     *   },
     *   lastNames: array{
     *     first: string,
     *     second: string,
     *   },
     *   birthDate: array{
     *     day: int,
     *     month: int,
     *     year: int,
     *   },
     *   gender: string,
     *   idCard: int,
     *   isFirstTime: bool,
     *   consultations: array{
     *     id: int,
     *     registeredDate: string,
     *     type: array{
     *       id: int,
     *       description: string,
     *     },
     *     department: array{
     *       id: int,
     *       name: string,
     *       registeredDate: string,
     *       icon: string,
     *       belongsToExternalConsultation: bool,
     *       isActive: bool,
     *       iconFilePath: string,
     *     },
     *     cause: array{
     *       id: int,
     *       name: array{
     *         short: string,
     *         extended: string,
     *       },
     *       code: string,
     *       category: array{
     *         id: int,
     *         name: array{
     *           short: string,
     *           extended: string,
     *         },
     *         parentCategory: ?array{
     *           id: int,
     *           name: array{
     *             short: string,
     *             extended: string,
     *           },
     *           parentCategory: null,
     *         },
     *       },
     *     },
     *   }[],
     * }
     *
     * @param array{
     *   id: int,
     *   registeredDate: string,
     *   type: array{
     *     id: int,
     *     description: string,
     *   },
     *   department: array{
     *     id: int,
     *     name: string,
     *     registeredDate: string,
     *     icon: string,
     *     belongsToExternalConsultation: bool,
     *     isActive: bool,
     *     iconFilePath: string,
     *   },
     *   cause: array{
     *     id: int,
     *     name: array{
     *       short: string,
     *       extended: string,
     *     },
     *     code: string,
     *     category: array{
     *       id: int,
     *       name: array{
     *         short: string,
     *         extended: string,
     *       },
     *       parentCategory: ?array{
     *         id: int,
     *         name: array{
     *           short: string,
     *           extended: string,
     *         },
     *         parentCategory: null,
     *       },
     *     },
     *   },
     * }[] $consultations
     */
    public function __invoke(
      Patient $patient,
      array $consultations
    ): array {
      return [
        'id' => $patient->id,
        'names' => [
          'first' => $patient->firstName,
          'second' => $patient->secondName,
        ],
        'lastNames' => [
          'first' => $patient->firstLastName,
          'second' => $patient->secondLastName,
        ],
        'birthDate' => [
          'day' => $patient->birthDate->day,
          'month' => $patient->birthDate->month,
          'year' => $patient->birthDate->year,
        ],
        'gender' => $patient->gender->value,
        'idCard' => $patient->idCard,
        'isFirstTime' => $consultations === [],
        'consultations' => $consultations,
      ];
    }
  };

  App::route(
    '/causas-consulta/categorias',
    static function () use ($categoryMapper): void {
      $categories = Container::getInstance()
        ->get(ConsultationCauseCategoryRepository::class)
        ->getAll();

      App::json(array_map($categoryMapper, $categories));
    }
  );

  App::route(
    '/causas-consulta/categorias/@id',
    static function (int $id) use ($categoryMapper, $causeMapper): void {
      $category = Container::getInstance()
        ->get(ConsultationCauseCategoryRepository::class)
        ->getById($id);

      if ($category === null) {
        App::notFound();

        return;
      }

      $causes = Container::getInstance()
        ->get(ConsultationCauseRepository::class)
        ->getByCategory($category);

      $data = $categoryMapper($category);
      $data['consultationCauses'] = array_map($causeMapper, $causes);

      App::json($data);
    }
  );

  App::route(
    '/causas-consulta',
    static function () use ($causeMapper): void {
      $causes = Container::getInstance()
        ->get(ConsultationCauseRepository::class)
        ->getAllWithGenerator();

      $data = [];

      foreach ($causes as $cause) {
        $data[] = $causeMapper($cause);
      }

      App::json($data);
    }
  );

  App::route(
    '/causas-consulta/@id',
    function (int $id) use ($causeMapper): void {
      $cause = Container::getInstance()
        ->get(ConsultationCauseRepository::class)
        ->getById($id);

      App::json($causeMapper($cause));
    }
  );

  App::route(
    '/pacientes/@patientId:[0-9]+/causas-consulta',
    static function (int $patientId) use (
      $consultationMapper,
      $patientMapper
    ): void {
      $patientRepository = Container::getInstance()
        ->get(PatientRepository::class);

      $patient = $patientRepository->getById($patientId);

      if ($patient === null) {
        App::json(['error' => "Paciente #{$patientId} no encontrado"], 404);

        return;
      }

      $patientRepository->setConsultations($patient);
      $consultations = [];

      foreach ($patient->getConsultation() as $consultation) {
        $consultations[] = $consultationMapper($consultation);
      }

      App::json($patientMapper($patient, $consultations));
    }
  );

  App::route(
    '/pacientes/@patientId:[0-9]+/causas-consulta/@causeId:[0-9]+',
    static function (
      int $patientId,
      int $causeId
    ) use ($consultationMapper, $patientMapper): void {
      $patientRepository = Container::getInstance()
        ->get(PatientRepository::class);

      $patient = $patientRepository->getById($patientId);

      if ($patient === null) {
        App::json(['error' => "Paciente #{$patientId} no encontrado"], 404);

        return;
      }

      $patientRepository->setConsultationsById($patient, $causeId);
      $consultations = [];

      foreach ($patient->getConsultation() as $consultation) {
        $consultations[] = $consultationMapper($consultation);
      }

      App::json($patientMapper($patient, $consultations));
    }
  );
});
