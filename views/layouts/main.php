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
  <link rel="stylesheet" href="./assets/fonts/fonts.css" />
  <link rel="stylesheet" href="./assets/vendors/metismenu/metisMenu.min.css" />
  <link rel="stylesheet" href="./assets/vendors/bootstrap/bootstrap.min.css" />
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
  <?php $mustChangePassword && $showPasswordChangeModal && render('components/confirmation', [
    'show' => true,
    'id' => 'change-password-confirmation',
    'action' => './perfil#seguridad',
    'title' => 'Se recomienda cambiar inmediatamente la contraseña',
    'confirmText' => 'Quiero cambiarla',
    'denyText' => 'Ignorar'
  ]); ?>
  <script src="./assets/vendors/jquery/jquery.min.js"></script>
  <script src="./assets/vendors/metismenu/metisMenu.min.js"></script>
  <script src="./assets/vendors/bootstrap/bootstrap.bundle.min.js"></script>
  <script src="./assets/js/custom.js"></script>
</body>

</html>
