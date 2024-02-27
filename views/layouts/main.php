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
  <link rel="icon" href="<?= asset('img/favicon.png') ?>" type="image/png" />
  <link rel="stylesheet" href="<?= asset('fonts/fonts.css') ?>" />
  <link rel="stylesheet" href="<?= asset('vendors/metismenu/metisMenu.min.css') ?>" />
  <link rel="stylesheet" href="<?= asset('vendors/bootstrap/bootstrap.min.css') ?>" />
  <link rel="stylesheet" href="<?= asset('vendors/themefy_icon/themify-icons.css') ?>" />
  <link rel="stylesheet" href="<?= asset('css/style1.css') ?>" />
  <link rel="stylesheet" href="<?= asset('css/custom.css') ?>" />
</head>

<body>
  <?php render('components/sidebar') ?>
  <section class="main_content">
    <?php render('components/header') ?>
    <div class="main_content_iner">
      <div class="container-fluid p-0">
        <div class="row justify-content-center">
          <?php $content ?>
        </div>
      </div>
    </div>
    <?php render('components/footer') ?>
  </section>
  <script src="<?= asset('js/jquery1-3.4.1.min.js') ?>"></script>
  <script src="<?= asset('vendors/metismenu/metisMenu.min.js') ?>"></script>
  <script src="<?= asset('js/custom.js') ?>"></script>
</body>

</html>
