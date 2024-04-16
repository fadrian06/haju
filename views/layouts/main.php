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
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,500;1,700&family=Rajdhani:wght@300;400;500;600;700&display=swap" />
  <link rel="stylesheet" href="https://unpkg.com/metismenu/dist/metisMenu.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
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
  <link rel="stylesheet" href="./assets/css/custom.css" />
  <style>
    .main_content {
      min-height: 100vh !important;
      display: grid;
      grid-template-rows: auto 1fr auto;
      grid-template-columns: 100%;
      align-items: start;
    }
  </style>
</head>

<body>
  <?php render('components/sidebar') ?>
  <section class="main_content pb-4 pt-0">
    <?php render('components/header') ?>
    <div class="main_content_iner row justify-content-center m-0 p-4">
      <?= $content ?>
    </div>
    <?php render('components/footer') ?>
  </section>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://unpkg.com/metismenu"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="./assets/js/custom.js"></script>
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
