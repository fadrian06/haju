<?php

declare(strict_types=1);

use HAJU\Controllers\APIController;

Flight::group('/api', static function (): void {
  Flight::route('GET /status', APIController::healthCheck(...));
  Flight::route('/preferencias/tema/@theme', APIController::setTheme(...));
  Flight::route('/verificar-clave-maestra', APIController::checkSecretKey(...));

  Flight::group('/causas-consulta', static function (): void {
    Flight::route('GET /', APIController::showAllConsultationCauses(...));
    Flight::route('GET /@id', APIController::showConsultationCauseDetails(...));

    Flight::group('/categorias', static function (): void {
      Flight::route(
        'GET /',
        APIController::showAllConsultationCauseCategories(...),
      );

      Flight::route(
        'GET /@id',
        APIController::showConsultationCauseCategoryDetails(...),
      );
    });
  });

  Flight::group('/pacientes', static function (): void {
    Flight::route(
      'GET /@patientId:[0-9]+/causas-consulta',
      APIController::showPatientDetails(...)
    );
  });
});
