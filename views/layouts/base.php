<?php

/** @var bool $showRegister */
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= $title ?> - HAJU</title>
  <link rel="icon" href="<?= asset('img/favicon.png') ?>" type="image/png" />
  <link rel="stylesheet" href="<?= asset('fonts/fonts.css') ?>" />
  <link rel="stylesheet" href="<?= asset('css/bootstrap1.min.css') ?>" />
  <link rel="stylesheet" href="<?= asset('vendors/themefy_icon/themify-icons.css') ?>" />
  <link rel="stylesheet" href="<?= asset('css/style1.css') ?>" />
  <link rel="stylesheet" href="<?= asset('css/custom.css') ?>" />
</head>

<body>
  <header class="header_iner d-flex justify-content-between align-items-center mb-4 py-1">
    <img src="<?= asset('img/logo.png') ?>" height="45" />
    <nav class="header_right d-flex align-items-center">
      <ul class="header_notification_warp mx-0 d-flex align-items-center">
        <li class="d-sm-none">
          <a href="<?= route('/ingresar') ?>">Iniciar sesión</a>
        </li>
        <?php if ($showRegister) : ?>
          <li>
            <a href="<?= route('/registrate') ?>">Regístrate</a>
          </li>
        <?php endif ?>
        <ul>
          <li>
            <img src="<?= asset('img/client_img.png') ?>" height="69" />
          </li>
        </ul>
      </ul>
    </nav>
  </header>
  <main class="main_content px-4 pb-0" style="min-height: unset">
    <div class="main_content_iner mb-4" style="min-height: unset">
      <div class="white_box p-4 row justify-content-center">
        <?= $content ?>
      </div>
    </div>
    <?php render('components/footer') ?>
  </main>

  <script src="<?= asset('js/bootstrap1.min.js') ?>"></script>
</body>

</html>
