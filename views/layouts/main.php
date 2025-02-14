<?php

use App\Models\User;

/**
 * @var string $root
 * @var string $title
 * @var string $content
 * @var User $user
 * @var bool $mustChangePassword
 */

$showPasswordChangeModal ??= $mustChangePassword;

if (str_contains($_SERVER['REQUEST_URI'], 'perfil')) {
  $showPasswordChangeModal = false;
}

$pdo = App::db()->instance();
$currentDate = new DateTimeImmutable;
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
  ':start_date' => $oneWeekAgo->format('Y-m-d') . ' 00:00:00',
  ':end_date' => $currentDate->format('Y-m-d') . ' 23:59:59'
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
    $epidemic = ['cause' => [
      'short_name' => str_replace(
        '  ',
        ' ',
        ($consultation['extended_name'] ?? $consultation['short_name']) . ' ' . $consultation['variant']
      )
    ]];
  }
}

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= $title ?> - HAJU</title>
  <?php render('components/open-graph-metas') ?>
  <base href="<?= $root ?>/" />
  <link rel="icon" href="./assets/img/logo-mini.png" />
  <link rel="stylesheet" href="./assets/fonts/fonts.css" />
  <link rel="stylesheet" href="./assets/vendors/metismenu/metisMenu.min.css" />
  <link rel="stylesheet" href="./vendor/twbs/bootstrap/dist/css/bootstrap.min.css" />
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
  <link rel="stylesheet" href="./assets/vendors/sweetalert2/default.min.css" />
  <script defer src="./vendor/faslatam/alpine-js/dist/cdn.min.js"></script>
  <link rel="stylesheet" href="./assets/css/custom.css" />
  <style>
    .main_content {
      min-height: 100vh !important;
      display: grid;
      grid-template-rows: auto 1fr auto;
      grid-template-columns: 100%;
      align-items: start;
    }

    body {
      padding-right: 0 !important;
    }
  </style>
</head>

<body>
  <?php if ($epidemic ?: false): ?>
    <?php renderComponent('epidemic-alert', ['epidemic' => $epidemic]) ?>
  <?php endif ?>
  <?php render('components/sidebar') ?>
  <section class="main_content pb-4 pt-0">
    <?php render('components/header') ?>
    <div class="main_content_iner row justify-content-center m-0 p-4">
      <?= $content ?>
    </div>
    <?php render('components/footer') ?>
  </section>
  <?php $mustChangePassword && $showPasswordChangeModal && render('components/confirmation', [
    'show' => true,
    'id' => 'change-password-confirmation',
    'action' => './perfil#seguridad',
    'title' => 'Debe cambiar la contraseña por seguridad',
    'confirmText' => 'Cambiarla',
    'denyText' => false
  ]); ?>
  <script src="./assets/vendors/jquery/jquery.min.js"></script>
  <script src="./assets/vendors/metismenu/metisMenu.min.js"></script>
  <script src="./vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="./assets/vendors/sweetalert2/sweetalert2.min.js"></script>
  <script src="./assets/vendors/ResizeObserver.global.js"></script>
  <script src="./assets/vendors/chart.js"></script>
  <!-- <script src="./assets/vendors/niceselect/js/jquery.nice-select.min.js"></script> -->
  <script>
    const swal = Swal.mixin({
      // toast: true,
      // position: 'top-right',
      showCloseButton: true,
      showConfirmButton: false
    })

    <?php if ($error): ?>
      swal.fire({
        title: '<?= $error ?>',
        icon: 'error'
      })
    <?php elseif ($message): ?>
      swal.fire({
        title: '<?= $message ?>',
        icon: 'success'
      })
    <?php endif ?>
  </script>
  <script src="./assets/js/custom.js"></script>
</body>

</html>
