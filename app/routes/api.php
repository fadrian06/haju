<?php

use App\Models\ConsultationCause;
use App\Models\ConsultationCauseCategory;
use MegaCreativo\API\CedulaVE;

App::group('/api', function (): void {
  $categoryMapper = new class {
    function __invoke(ConsultationCauseCategory $category): array {
      return [
        'id' => $category->id,
        'name' => [
          'short' => $category->shortName,
          'extended' => $category->extendedName
        ],
        'parentCategory' => $category->parentCategory
          ? ($this)($category->parentCategory)
          : null
      ];
    }
  };

  $causeMapper = new class {
    public ?object $categoryMapper = null;

    function __invoke(ConsultationCause $cause): array {
      $result = [
        'id' => $cause->id,
        'name' => [
          'short' => $cause->getFullName(),
          'extended' => $cause->getFullName(abbreviated: false)
        ],
        'code' => $cause->code
      ];

      if ($this->categoryMapper) {
        $result['category'] = ($this->categoryMapper)($cause->category);
      }

      return $result;
    }
  };

  App::route(
    '/causas-consulta/categorias',
    function () use ($categoryMapper): void {
      $categories = App::consultationCauseCategoryRepository()->getAll();

      App::json(array_map([$categoryMapper, '__invoke'], $categories));
    }
  );

  App::route(
    '/causas-consulta/categorias/@id',
    function (int $id) use ($categoryMapper, $causeMapper): void {
      $category = App::consultationCauseCategoryRepository()->getById($id);
      $causes = App::consultationCauseRepository()->getByCategory($category);

      App::json([
        ...$categoryMapper($category),
        'consultationCauses' => array_map([$causeMapper, '__invoke'], $causes)
      ]);
    }
  );

  App::route('/causas-consulta', function () use ($categoryMapper): void {
    $causes = App::consultationCauseRepository()->getAllWithGenerator();
    $json = [];

    foreach ($causes as $cause) {
      $json[] = [
        'id' => $cause->id,
        'name' => [
          'short' => $cause->getFullName(),
          'extended' => $cause->getFullName(abbreviated: false)
        ],
        'code' => $cause->code,
        'category' => $categoryMapper($cause->category)
      ];
    }

    App::json($json);
  });

  App::route(
    '/causas-consulta/@id',
    function (int $id) use ($categoryMapper): void {
      $cause = App::consultationCauseRepository()->getById($id);

      App::json([
        'id' => $cause->id,
        'name' => [
          'short' => $cause->getFullName(),
          'extended' => $cause->getFullName(abbreviated: false)
        ],
        'code' => $cause->code,
        'category' => $categoryMapper($cause->category)
      ]);
    }
  );

  App::route('/pacientes', function (): void {
  });

  App::route('/pacientes/@patientId:[0-9]+', function (int $patientId): void {
  });

  App::route(
    '/pacientes/@patientId:[0-9]+/causas-consulta',
    function (int $patientId) use ($causeMapper, $categoryMapper): void {
      $patient = App::patientRepository()->getById($patientId);

      if (!$patient) {
        App::json(['error' => "Paciente #$patientId no encontrado"], 404);

        return;
      }

      App::patientRepository()->setConsultations($patient);
      $causeMapper->categoryMapper = $categoryMapper;

      $consultations = [];

      foreach ($patient->getConsultation() as $consultation) {
        $consultations[] = [
          'id' => $consultation->id,
          'registeredDate' => $consultation->registeredDate,
          'type' => [
            'id' => $consultation->type->value,
            'description' => $consultation->type->getDescription()
          ],
          'department' => [
            'id' => $consultation->department->id,
            'name' => $consultation->department->name,
            'registeredDate' => $consultation->department->registeredDate,
            'icon' => urldecode($consultation->department->iconFilePath->asString()),
            'belongsToExternalConsultation' => $consultation->department->belongsToExternalConsultation,
            'isActive' => $consultation->department->isActive()
          ],
          'cause' => $causeMapper($consultation->cause)
        ];
      }

      App::json([
        'id' => $patient->id,
        'names' => [
          'first' => $patient->firstName,
          'second' => $patient->secondName
        ],
        'lastNames' => [
          'first' => $patient->firstLastName,
          'second' => $patient->secondLastName
        ],
        'birthDate' => [
          'day' => $patient->birthDate->day,
          'month' => $patient->birthDate->month,
          'year' => $patient->birthDate->year
        ],
        'gender' => $patient->gender->value,
        'idCard' => $patient->idCard,
        'consultations' => $consultations,
      ]);
    }
  );

  App::route(
    '/pacientes/@patientId:[0-9]+/causas-consulta/@causeId:[0-9]+',
    function (
      int $patientId,
      int $causeId
    ) use (
      $causeMapper,
      $categoryMapper
    ): void {
      $patient = App::patientRepository()->getById($patientId);

      if (!$patient) {
        App::json(['error' => "Paciente #$patientId no encontrado"], 404);

        return;
      }

      App::patientRepository()->setConsultationsById($patient, $causeId);
      $causeMapper->categoryMapper = $categoryMapper;

      $consultations = [];

      foreach ($patient->getConsultation() as $consultation) {
        $consultations[] = [
          'id' => $consultation->id,
          'registeredDate' => $consultation->registeredDate,
          'type' => [
            'id' => $consultation->type->value,
            'description' => $consultation->type->getDescription()
          ],
          'department' => [
            'id' => $consultation->department->id,
            'name' => $consultation->department->name,
            'registeredDate' => $consultation->department->registeredDate,
            'icon' => urldecode($consultation->department->iconFilePath->asString()),
            'belongsToExternalConsultation' => $consultation->department->belongsToExternalConsultation,
            'isActive' => $consultation->department->isActive()
          ],
          'cause' => $causeMapper($consultation->cause)
        ];
      }

      App::json([
        'id' => $patient->id,
        'names' => [
          'first' => $patient->firstName,
          'second' => $patient->secondName
        ],
        'lastNames' => [
          'first' => $patient->firstLastName,
          'second' => $patient->secondLastName
        ],
        'birthDate' => [
          'day' => $patient->birthDate->day,
          'month' => $patient->birthDate->month,
          'year' => $patient->birthDate->year
        ],
        'gender' => $patient->gender->value,
        'idCard' => $patient->idCard,
        'isFirstTime' => $consultations === [],
        'consultations' => $consultations,
      ]);
    }
  );

  App::route('/cedulacion/@idCard', function (int $idCard): void {
    App::json(@CedulaVE::get('V', $idCard));
  });
});
