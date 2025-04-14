<?php

declare(strict_types=1);

use App\Models\ConsultationCause;
use App\Models\ConsultationCauseCategory;
use App\Repositories\Domain\ConsultationCauseRepository;
use flight\Container;
use flight\template\View;

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

$causes = Container::getInstance()
  ->get(ConsultationCauseRepository::class)
  ->getAllWithGenerator();

$data = [];

foreach ($causes as $cause) {
  $data[] = $causeMapper($cause);
}

$causes = $data;

/** @var array<int, ConsultationCauseCategory> */
$categories = [];

$monthYear = $_GET['fecha'] ?? null;

ob_start();

$startDate ??= null;
$endDate ??= null;

if ($monthYear !== null) {
  [$year, $month] = explode('-', (string) $monthYear);

  $daysOfMonth = match ($month) {
    '01', '03', '05', '07', '08', '10', '12' => 31,
    '04', '06', '09', '11' => 30,
    '02' => $year % 4 === 0 && ($year % 100 !== 0 || $year % 400 === 0)
      ? 29
      : 28
  };

  $startDate = (new View)->e("{$monthYear}-01");
  $endDate = (new View)->e("{$monthYear}-{$daysOfMonth}");
}

ob_end_clean();

$causeCounter = 1;
$categoryCounter = 1;
$printedParentCategories = [];

$consultations = Container::getInstance()->get(PDO::class)->query(<<<sql
  SELECT type, cause_id FROM consultations
  WHERE registered_date BETWEEN '{$startDate}' AND '{$endDate}'
sql)->fetchAll(PDO::FETCH_ASSOC);

$typesByCause = [];

foreach ($consultations as $consultation) {
  $typesByCause[$consultation['cause_id']] ??= [
    'P' => 0,
    'S' => 0,
    'X' => 0
  ];

  ++$typesByCause[$consultation['cause_id']][$consultation['type']];
}

$monthName = [
  1 => 'Enero',
  2 => 'Febrero',
  3 => 'Marzo',
  4 => 'Abril',
  5 => 'Mayo',
  6 => 'Junio',
  7 => 'Julio',
  8 => 'Agosto',
  9 => 'Septiembre',
  10 => 'Octubre',
  11 => 'Noviembre',
  12 => 'Diciembre'
];

?>

<div class="row justify-content-between align-items-center">
  <img src="./assets/img/gob.png" class="col-md-5 img-fluid" />
  <img src="./assets/img/sis.png" class="col-md-2 img-fluid" />
  <h2 class="col-md-5 text-end">SIS-04/EPI-15</h2>
  <div class="col-md-6 fw-bold">
    Viceministerio de Redes de Salud Colectiva<br />
    Direccion General de Epidemiologia<br />
    Direcciòn de Vigilancia Epidemiologica
  </div>
  <div class="col-md-3">
    <div class="input-group mb-3">
      <label style="flex-basis: 60px" class="input-group-text">MES</label>
      <input
        readonly
        value="<?= $monthName[(int) explode('-', (string) $monthYear)[1]] ?>"
        class="form-control" />
    </div>
    <div class="input-group mb-3">
      <label style="flex-basis: 60px" class="input-group-text">AÑO</label>
      <input
        type="number"
        readonly
        value="<?= explode('-', (string) $monthYear)[0] ?>"
        class="form-control" />
    </div>
  </div>
</div>

<h1 class="h5 text-center">
  CONSOLIDADO MENSUAL MORBILIDAD REGISTRADA POR ENFERMEDADES, APARATOS Y
  SISTEMAS
</h1>

<div class="p-1">
  <fieldset class="px-5 py-2 border border-dark mb-3">
    <legend class="fw-bold">Identificación del Establecimiento</legend>
    <div class="row">
      <div class="col-md-6">
        <div class="input-group mb-3">
          <label class="input-group-text">Entidad Federal</label>
          <input readonly class="form-control" />
        </div>
      </div>
      <div class="col-md-6">
        <div class="input-group mb-3">
          <label class="input-group-text">Municipio</label>
          <input readonly class="form-control" />
        </div>
      </div>
      <div class="col-md-6">
        <div class="input-group mb-3">
          <label class="input-group-text">Parroquia</label>
          <input readonly class="form-control" />
        </div>
      </div>
      <div class="col-md-6">
        <div class="input-group mb-3">
          <label class="input-group-text">Localidad</label>
          <input readonly class="form-control" />
        </div>
      </div>
      <div class="col-md-8">
        <div class="input-group mb-3">
          <label class="input-group-text">Nombre del establecimiento</label>
          <input readonly class="form-control" />
        </div>
      </div>
      <div class="col-md-4">
        <div class="input-group mb-3">
          <label class="input-group-text">Tipo</label>
          <input readonly class="form-control" />
        </div>
      </div>
    </div>
  </fieldset>
  <table style="width: 100%" class="w3-table w3-centered w3-bordered table table-hover">
    <thead>
      <tr>
        <th rowspan="3" colspan="2">ENFERMEDADES</th>
        <th colspan="5">NÚMERO DE CASOS</th>
      </tr>
      <tr>
        <th data-bs-toggle="tooltip" title="Primera vez">P</th>
        <th data-bs-toggle="tooltip" title="Sucesiva">S</th>
        <th data-bs-toggle="tooltip" title="Asociada">X</th>
        <th>P + X</th>
        <th rowspan="2">Acumulado<br />del año</th>
      </tr>
      <tr>
        <th colspan="3"></th>
        <th>TOTAL</th>
      </tr>
    </thead>
    <tbody class="text-uppercase">
      <?php foreach ($causes as $cause): ?>
        <?php if (!array_key_exists($cause['category']['id'], $categories)): ?>
          <?php $categories[$cause['category']['id']] = $cause['category'] ?>
          <tr>
            <td class="fw-bold" colspan="7" style="text-align: start">
              <?php if (
                is_array($cause['category']['parentCategory'])
                && !in_array($cause['category']['parentCategory'], $printedParentCategories, true)
              ): ?>
                <?= $cause['category']['parentCategory']['name']['extended'] ?? $cause['category']['parentCategory']['name']['short'] ?>
                <br />
                <?php $printedParentCategories[] = $cause['category']['parentCategory'] ?>
              <?php endif ?>
              <?= $cause['category']['name']['extended'] ?? $cause['category']['name']['short'] ?>
            </td>
          </tr>
        <?php endif ?>
        <tr id="<?= $cause['id'] ?>">
          <th><?= $causeCounter++ ?></th>
          <td style="text-align: start"><?= $cause['name']['extended'] ?></td>
          <td
            id="cause<?= $cause['id'] ?>-P"
            data-bs-toggle="tooltip"
            title="P">
          </td>
          <td
            id="cause<?= $cause['id'] ?>-S"
            data-bs-toggle="tooltip"
            title="S">
          </td>
          <td
            id="cause<?= $cause['id'] ?>-X"
            data-bs-toggle="tooltip"
            title="X">
          </td>
          <td
            id="cause<?= $cause['id'] ?>-PX"
            data-bs-toggle="tooltip"
            title="P + X">
          </td>
          <td
            id="cause<?= $cause['id'] ?>-A"
            data-bs-toggle="tooltip"
            title="Acumulado del año">
          </td>
        </tr>
      <?php endforeach ?>
    </tbody>
    <tfoot class="text-uppercase">
      <tr>
        <th colspan="2">TOTAL CAUSAS DE CONSULTA</th>
        <td id="total-P" data-bs-toggle="tooltip" title="P"></td>
        <td id="total-S" data-bs-toggle="tooltip" title="S"></td>
        <td id="total-X" data-bs-toggle="tooltip" title="X"></td>
        <td id="total-PX" data-bs-toggle="tooltip" title="P + X"></td>
        <td id="total-A" data-bs-toggle="tooltip" title="Acumulado del año"></td>
      </tr>
    </tfoot>
  </table>

  <div class="fw-bold row">
    <span class="col-md-1">(a*):</span>
    <span class="col-md-11">
      CHAGAS AGUDO SE INCLUYE EN ENFERMEDAD DE CHAGAS.
    </span>
    <span class="col-md-1">(a**):</span>
    <span class="col-md-11">
      ACCIDENTES DE TRANSPORTE TERRESTRE, DEL HOGAR,
      LABORALES Y OTROS ACCIDENTES, SE EXCLUYEN DEL CONTEO DEL TOTAL DE
      CAUSAS. SE SUPONEN INCLUIDAS EN HERIDAS, FRACTURAS Y/O LUXACIONES,
      ESGUINCES Y OTROS TRAUMATISMOS.
    </span>
    <span class="col-md-12 my-2">Para calcular las tasas:</span>
    <span class="col-md-1">(*):</span>
    <span class="col-md-11">TASAS POR 100.000 N.V.R.</span>
    <span class="col-md-1">(**):</span>
    <span class="col-md-11">
      TASAS ESPECIFICAS POR 100.000 MUJERES ENTRE 15 Y 49 AÑOS DE EDAD.
    </span>
    <span class="col-md-1">(***):</span>
    <span class="col-md-11">
      TASAS ESPECIFICAS POR 100.000 HABS. DEL CORRESPONDIENTE GRUPO DE EDAD.
    </span>
    <span class="col-md-12">
      NOTA: LA TASA GENERAL DE MORBILIDAD REGISTRADA (TOTAL CAUSAS) ES POR
      1.000 HABITANTES.
    </span>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const typesByCause = JSON.parse('<?= json_encode($typesByCause) ?>')

    const $totalP = document.querySelector('#total-P')
    const $totalS = document.querySelector('#total-S')
    const $totalX = document.querySelector('#total-X')
    const $totalPX = document.querySelector('#total-PX')
    const $totalA = document.querySelector('#total-A')

    Object.keys(typesByCause).forEach(causeId => {
      const $p = document.querySelector(`#cause${causeId}-P`)
      const $s = document.querySelector(`#cause${causeId}-S`)
      const $x = document.querySelector(`#cause${causeId}-X`)
      const $px = document.querySelector(`#cause${causeId}-PX`)
      const $accumulated = document.querySelector(`#cause${causeId}-A`)

      $p.innerText = typesByCause[causeId].P
      $s.innerText = typesByCause[causeId].S
      $x.innerText = typesByCause[causeId].X
      $px.innerText = typesByCause[causeId].P + typesByCause[causeId].X
      $accumulated.innerText = typesByCause[causeId].P + typesByCause[causeId].S + typesByCause[causeId].X

      $totalP.innerText = parseInt($totalP.innerText || 0) + typesByCause[causeId].P
      $totalS.innerText = parseInt($totalS.innerText || 0) + typesByCause[causeId].S
      $totalX.innerText = parseInt($totalX.innerText || 0) + typesByCause[causeId].X

      $totalPX.innerText = parseInt($totalP.innerText) + parseInt($totalX.innerText)
      $totalA.innerText = parseInt($totalP.innerText) +
        parseInt($totalS.innerText) +
        parseInt($totalX.innerText)
    })

    print()
  })
</script>
