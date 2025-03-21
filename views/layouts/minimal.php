<?php

use App\Models\User;

/**
 * @var string $root
 * @var string $title
 * @var string $content
 * @var User $user
 */

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width" />
  <title><?= $title ?> - HAJU</title>
  <?php render('components/open-graph-metas') ?>
  <base href="<?= $root ?>/" />
  <link rel="icon" href="./assets/img/logo-mini.png" />
  <link rel="stylesheet" href="./vendor/twbs/bootstrap/dist/css/bootstrap.min.css" />
  <style>
    .w3-bordered th,
    .w3-bordered td {
      vertical-align: middle;
      box-sizing: border-box;
      border: medium solid black;
      padding: .25em .5em;
    }

    .w3-table {
      border-collapse: collapse;
      text-align: center;
    }

    .form-control {
      border-radius: unset;
      border-color: black;
    }

    .input-group-text {
      border-radius: unset;
      border-color: black;
      background: unset;
    }
  </style>
</head>

<body>
  <section class="main_content pb-4 pt-0">
    <div class="main_content_iner row justify-content-center m-0 p-4">
      <?= $content ?>
    </div>
  </section>
  <script src="./vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    for (const tooltipTriggerEl of document.querySelectorAll('[data-bs-toggle="tooltip"]')) {
      new bootstrap.Tooltip(tooltipTriggerEl)
    }
  </script>
</body>

</html>
