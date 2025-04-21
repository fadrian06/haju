<?php

declare(strict_types=1);

use HAJU\Models\User;
use HAJU\Repositories\Domain\PatientRepository;
use flight\Container;
use Leaf\Http\Session;

/**
 * @var string $title
 * @var string $content
 * @var User $user
 * @var bool $mustChangePassword
 */

$showPasswordChangeModal ??= $mustChangePassword;

if (str_contains(strval($_SERVER['REQUEST_URI']), 'perfil')) {
  $showPasswordChangeModal = false;
}

$pdo = Container::getInstance()->get(PDO::class);
$currentDate = new DateTimeImmutable();
$oneWeekAgo = $currentDate->sub(new DateInterval('P1W'));

// TODO: group by patient_id + cause_id to skip consultations of same patient and the same consultation cause
$stmt = $pdo->prepare('
  SELECT short_name, variant, extended_name, weekly_cases_limit, cause_id, patient_id
  FROM consultations
  JOIN consultation_causes
  ON consultations.cause_id = consultation_causes.id
  WHERE consultations.registered_date BETWEEN :start_date AND :end_date
');

$stmt->execute([
  ':start_date' => "{$oneWeekAgo->format('Y-m-d')} 00:00:00",
  ':end_date' => "{$currentDate->format('Y-m-d')} 23:59:59",
]);

$consultations = $stmt->fetchAll(PDO::FETCH_ASSOC);
$causesGroupedByCauseId = [];
$epidemic = false;

foreach ($consultations as $consultation) {
  if ($consultation['weekly_cases_limit'] === null) {
    continue;
  }

  $causeId = $consultation['cause_id'];
  $causesGroupedByCauseId[$causeId]['weeklyLimit'] = $consultation['weekly_cases_limit'];
  $causesGroupedByCauseId[$causeId]['consultations'][] = $consultation;
  $consultationsCount = count($causesGroupedByCauseId[$causeId]['consultations']);
  $weeklyLimit = $causesGroupedByCauseId[$causeId]['weeklyLimit'];

  if ($consultationsCount > $weeklyLimit) {
    $epidemic = [
      'cause' => [
        'short_name' => str_replace(
          '  ',
          ' ',
          ($consultation['extended_name'] ?? $consultation['short_name']) . ' ' . $consultation['variant']
        ),
      ],
      'patient' => Container::getInstance()
        ->get(PatientRepository::class)
        ->getById(intval($consultation['patient_id'])),
    ];
  }
}

?>

<!DOCTYPE html>
<html
  lang="es"
  x-data="{
    theme: `<?= Session::get('theme', 'light') ?>`,

    setTheme(theme = 'light') {
      this.theme = theme;
      fetch(`./api/preferencias/tema/${theme}`);
    },
  }"
  data-bs-theme="<?= Session::get('theme', 'light') ?>"
  :data-bs-theme="theme">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width" />
  <title><?= $title ?> - HAJU</title>
  <?php Flight::render('components/open-graph-metas') ?>
  <base href="<?= str_replace('index.php', '', $_SERVER['SCRIPT_NAME']) ?>" />
  <link rel="icon" href="./resources/images/favicon.svg" />
  <link rel="stylesheet" href="./resources/dist/main.css" />
  <link rel="stylesheet" href="./assets/vendors/themefy_icon/themify-icons.css" />
  <link rel="stylesheet" href="./assets/css/reset.css" />
  <link rel="stylesheet" href="./assets/css/utils.css" />
  <link rel="stylesheet" href="./assets/css/components/btn.css" />
  <link rel="stylesheet" href="./assets/css/components/sidebar.css" />
  <link rel="stylesheet" href="./assets/css/components/main.css" />
  <link rel="stylesheet" href="./assets/css/components/header.css" />
  <link rel="stylesheet" href="./assets/css/components/sidebar.css" />
  <link rel="stylesheet" href="./assets/css/components/profile.css" />
  <link rel="stylesheet" href="./assets/css/components/footer.css" />
  <link rel="stylesheet" href="./assets/css/components/single-element.css" />
  <link rel="stylesheet" href="./assets/css/components/box.css" />
  <link rel="stylesheet" href="./assets/css/components/section.css" />
  <link rel="stylesheet" href="./assets/css/components/search-field.css" />
  <link rel="stylesheet" href="./assets/css/custom.css" />
</head>

<body>
  <?php if ($epidemic) : ?>
    <?php Flight::render('components/epidemic-alert', ['epidemic' => $epidemic]) ?>
  <?php endif ?>
  <?php Flight::render('components/sidebar') ?>
  <section class="main_content pb-4 pt-0">
    <?php Flight::render('components/header') ?>
    <div class="main_content_iner row justify-content-center m-0 p-4">
      <?= $content ?>
    </div>
    <?php Flight::render('components/footer') ?>
  </section>
  <?php if ($mustChangePassword && $showPasswordChangeModal) : ?>
    <?php Flight::render('components/confirmation', [
      'show' => true,
      'id' => 'change-password-confirmation',
      'action' => './perfil#seguridad',
      'title' => 'Debe cambiar la contraseña por seguridad',
      'confirmText' => 'Cambiarla',
      'denyText' => false
    ]) ?>
  <?php endif ?>
  <script src="./assets/vendors/jquery/jquery.min.js"></script>
  <script src="./assets/vendors/metismenu/metisMenu.min.js"></script>
  <script src="./assets/vendors/chart.js"></script>
  <script src="./resources/dist/main.js"></script>
  <script src="./assets/js/custom.js" defer></script>

  <script>
    <?php if ($error) : ?>
      customSwal.fire({
        title: '<?= $error ?>',
        icon: 'error',
      })
    <?php elseif ($message) : ?>
      customSwal.fire({
        title: '<?= $message ?>',
        icon: 'success',
      })
    <?php endif ?>
  </script>

</body>

</html>
