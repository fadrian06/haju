<?php

/** @var bool $showRegister */
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= $title ?> - HAJU</title>
  <?php render('components/open-graph-metas') ?>
  <link rel="icon" href="<?= asset('img/favicon.png') ?>" type="image/png" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,500;1,700&family=Rajdhani:wght@300;400;500;600;700&display=swap" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="<?= asset('vendors/themefy_icon/themify-icons.css') ?>" />
  <link rel="stylesheet" href="<?= asset('css/theme.css') ?>" />
  <link rel="stylesheet" href="<?= asset('css/custom.css') ?>" />
</head>

<body class="pb-4">
  <header class="d-flex header_iner align-items-center py-0">
    <img src="<?= asset('img/logo.png') ?>" height="45" />
    <nav class="header_right">
      <ul class="header_notification_warp d-flex align-items-center mx-0">
        <li class="d-none d-md-block">
          <a href="<?= route('/ingresar') ?>">Iniciar sesión</a>
        </li>
        <?php if ($showRegister) : ?>
          <li class="d-none d-md-block">
            <a href="<?= route('/registrate') ?>">Regístrate</a>
          </li>
        <?php endif ?>
        <li>
          <img src="<?= asset('img/client_img.png') ?>" height="69" />
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
