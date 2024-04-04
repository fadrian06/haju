<?php

namespace App\Controllers\Web;

use App;
use App\Repositories\Domain\PatientRepository;

final class PatientWebController extends Controller {
  private readonly PatientRepository $repository;

  function __construct() {
    parent::__construct();

    $this->repository = App::patientRepository();
  }

  function showPatients(): void {
    header('Content-Type: text/plain');
    print_r($this->repository->getAll());
  }
}
