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
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= $title ?> - HAJU</title>
  <?php render('components/open-graph-metas') ?>
  <base href="<?= $root ?>/" />
  <link rel="icon" href="./assets/img/logo-mini.png" />
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css" />
  <style>
    .w3-bordered th,
    .w3-bordered td {
      vertical-align: middle;
      box-sizing: border-box;
      border: medium solid black;
      padding: .25em .5em;
    }
  </style>
</head>

<body>
  <section class="main_content pb-4 pt-0">
    <div class="main_content_iner row justify-content-center m-0 p-4">
      <?= $content ?>
    </div>
  </section>
</body>

</html>
