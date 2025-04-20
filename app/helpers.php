<?php

declare(strict_types=1);

use flight\Container;
use flight\template\View;
use HAJU\Models\Consultation;
use HAJU\Models\ConsultationCause;
use HAJU\Models\ConsultationCauseCategory;
use HAJU\Models\Patient;

const ROOT_PATH = __DIR__ . '/..';
const APP_PATH = ROOT_PATH . '/app';
const CONFIGURATIONS_PATH = ROOT_PATH . '/config';
const LOGS_PATH = ROOT_PATH . '/storage/logs';
const ROUTES_PATH = ROOT_PATH . '/routes';
const DATABASE_PATH = ROOT_PATH . '/database';
const VIEWS_PATH = ROOT_PATH . '/resources/views';

$_SERVER['SCRIPT_NAME'] ??= '/index.php';
$_SERVER['HTTP_HOST'] ??= 'localhost:61001';

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

function renderPage(
  string $page,
  string $title,
  array $params = [],
  string $layout = 'guest'
): void {
  $params['title'] = $title;
  $view = Container::getInstance()->get(View::class);

  $params['content'] = $view->fetch("pages/{$page}", $params);
  $view->render("layouts/{$layout}", $params);
}

function isActive(string ...$urls): bool
{
  foreach ($urls as $url) {
    if ($url === '/') {
      if (Flight::request()->url === $url) {
        return true;
      }
    } elseif (str_starts_with(Flight::request()->url, $url)) {
      return true;
    }
  }

  return false;
}

define('CATEGORY_MAPPER', new class {
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
  public function __invoke(ConsultationCauseCategory $category): array
  {
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
});

define('CAUSE_MAPPER', new class {
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
  public function __invoke(ConsultationCause $cause): array
  {
    return [
      'id' => $cause->id,
      'name' => [
        'short' => $cause->getFullName(),
        'extended' => $cause->getFullName(abbreviated: false),
      ],
      'code' => $cause->code,
      'category' => (CATEGORY_MAPPER)->__invoke($cause->category),
    ];
  }
});

define('CONSULTATION_MAPPER', new class() {
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
  public function __invoke(Consultation $consultation): array
  {
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
      'cause' => (CAUSE_MAPPER)->__invoke($consultation->cause),
    ];
  }
});

define('PATIENT_MAPPER', new class {
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
  public function __invoke(Patient $patient, array $consultations): array
  {
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
});
