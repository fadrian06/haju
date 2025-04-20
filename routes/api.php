<?php

declare(strict_types=1);

use HAJU\Repositories\Domain\ConsultationCauseCategoryRepository;
use HAJU\Repositories\Domain\ConsultationCauseRepository;
use HAJU\Repositories\Domain\PatientRepository;
use flight\Container;
use Leaf\Http\Session;

Flight::group('/api', static function (): void {
  Flight::route(
    'GET /status',
    static fn() => Flight::json(['status' => 'ok'])
  );

  Flight::route(
    '/preferencias/tema/@theme',
    static function (string $theme): void {
      Session::set('theme', $theme);
    }
  );

  Flight::route('/verificar-clave-maestra', static function (): void {
    $secretKey = Flight::request()->data['secret_key'];

    if ($secretKey !== '1234') {
      Flight::json('Clave maestra incorrecta', 401);
    } else {
      Session::set('let_register_director', true);
    }
  });

  Flight::route(
    '/causas-consulta/categorias',
    static function (): void {
      $categories = Container::getInstance()
        ->get(ConsultationCauseCategoryRepository::class)
        ->getAll();

      Flight::json(array_map(CATEGORY_MAPPER, $categories));
    }
  );

  Flight::route(
    '/causas-consulta/categorias/@id',
    static function (int $id): void {
      $category = Container::getInstance()
        ->get(ConsultationCauseCategoryRepository::class)
        ->getById($id);

      if ($category === null) {
        Flight::notFound();

        return;
      }

      $causes = Container::getInstance()
        ->get(ConsultationCauseRepository::class)
        ->getAllByCategory($category);

      $data = (CATEGORY_MAPPER)->__invoke($category);
      $data['consultationCauses'] = array_map(CAUSE_MAPPER, $causes);

      Flight::json($data);
    }
  );

  Flight::route(
    '/causas-consulta',
    static function (): void {
      $causes = Container::getInstance()
        ->get(ConsultationCauseRepository::class)
        ->getAllWithGenerator();

      $data = [];

      foreach ($causes as $cause) {
        $data[] = (CAUSE_MAPPER)->__invoke($cause);
      }

      Flight::json($data);
    }
  );

  Flight::route(
    '/causas-consulta/@id',
    function (int $id): void {
      $cause = Container::getInstance()
        ->get(ConsultationCauseRepository::class)
        ->getById($id);

      Flight::json((CAUSE_MAPPER)->__invoke($cause));
    }
  );

  Flight::route(
    '/pacientes/@patientId:[0-9]+/causas-consulta',
    static function (int $patientId): void {
      $patientRepository = Container::getInstance()
        ->get(PatientRepository::class);

      $patient = $patientRepository->getById($patientId);

      if ($patient === null) {
        Flight::json(['error' => "Paciente #{$patientId} no encontrado"], 404);

        return;
      }

      $patientRepository->setConsultations($patient);
      $consultations = [];

      foreach ($patient->getConsultation() as $consultation) {
        $consultations[] = (CONSULTATION_MAPPER)->__invoke($consultation);
      }

      Flight::json((PATIENT_MAPPER)->__invoke($patient, $consultations));
    }
  );

  Flight::route(
    '/pacientes/@patientId:[0-9]+/causas-consulta/@causeId:[0-9]+',
    static function (int $patientId, int $causeId): void {
      $patientRepository = Container::getInstance()
        ->get(PatientRepository::class);

      $patient = $patientRepository->getById($patientId);

      if ($patient === null) {
        Flight::json(['error' => "Paciente #{$patientId} no encontrado"], 404);

        return;
      }

      $patientRepository->setConsultationsById($patient, $causeId);
      $consultations = [];

      foreach ($patient->getConsultation() as $consultation) {
        $consultations[] = (CONSULTATION_MAPPER)->__invoke($consultation);
      }

      Flight::json((PATIENT_MAPPER)->__invoke($patient, $consultations));
    }
  );
});
