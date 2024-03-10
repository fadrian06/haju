<?php

/** @var App\Models\User $user */

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= $title ?> - HAJU</title>
  <?php render('components/open-graph-metas') ?>
  <link rel="icon" href="<?= asset('img/logo-mini.png') ?>" />
  <link rel="stylesheet" href="<?= asset('fonts/fonts.css') ?>" />
  <link rel="stylesheet" href="<?= asset('vendors/metismenu/metisMenu.min.css') ?>" />
  <link rel="stylesheet" href="<?= asset('vendors/bootstrap/bootstrap.min.css') ?>" />
  <link rel="stylesheet" href="<?= asset('vendors/themefy_icon/themify-icons.css') ?>" />
  <link rel="stylesheet" href="<?= asset('css/theme.css') ?>" />
  <link rel="stylesheet" href="<?= asset('css/custom.css') ?>" />
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
  <section class="main_content pb-4">
    <?php render('components/header') ?>
    <div class="main_content_iner row justify-content-center m-0 p-4">
      <?= $content ?>
    </div>
    <?php render('components/footer') ?>
  </section>
  <script src="<?= asset('vendors/jquery/jquery.min.js') ?>"></script>
  <script src="<?= asset('vendors/metismenu/metisMenu.min.js') ?>"></script>
  <script src="<?= asset('vendors/bootstrap/bootstrap.bundle.min.js') ?>"></script>
  <script src="<?= asset('js/custom.js') ?>"></script>
</body>

</html>
