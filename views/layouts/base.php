<?php

use App\Models\User;
use PharIo\Manifest\Url;

/**
 * @var string $root
 * @var string $title
 * @var string $content
 * @var ?User $user
 */

if (isset($user)) {
  $user->profileImagePath = new Url(urldecode($user->profileImagePath->asString()));
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
  <link rel="stylesheet" href="./assets/vendors/bootstrap/bootstrap.min.css" />
  <link rel="stylesheet" href="./assets/vendors/themefy_icon/themify-icons.css" />
  <link rel="stylesheet" href="./assets/css/reset.css" />
  <link rel="stylesheet" href="./assets/css/utils.css" />
  <link rel="stylesheet" href="./assets/css/components/header.css" />
  <link rel="stylesheet" href="./assets/css/components/modal.css" />
  <link rel="stylesheet" href="./assets/css/components/btn.css" />
  <link rel="stylesheet" href="./assets/css/components/footer.css" />
  <link rel="stylesheet" href="./assets/css/components/main.css" />
  <link rel="stylesheet" href="./assets/css/components/modal.css" />
  <link rel="stylesheet" href="./assets/css/components/box.css" />
  <link rel="stylesheet" href="./assets/css/custom.css" />
</head>

<body class="pb-4">
  <header class="d-flex header_iner align-items-center py-0">
    <img src="./assets/img/logo.png" height="45" />
    <nav class="header_right">
      <ul class="header_notification_warp d-flex align-items-center mx-0">
        <?php if (isActive('/departamento/seleccionar')): ?>
          <li class="d-none d-md-block">
            <a href="./salir">Cerrar sesión</a>
          </li>
        <?php endif ?>
        <li>
          <img class="rounded-circle" src="<?= $user?->profileImagePath->asString() ?? './assets/img/client_img.png' ?>" height="69" />
        </li>
      </ul>
    </nav>
  </header>
  <main class="main_content p-2 px-md-5 py-md-0">
    <div class="main_content_iner mx-0 my-4 white_box row justify-content-center">
      <?= $content ?>
    </div>
  </main>
  <?php render('components/footer') ?>
  <script src="./assets/vendors/bootstrap/bootstrap.bundle.min.js"></script>
</body>

</html>
