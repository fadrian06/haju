<?php

declare(strict_types=1);

use HAJU\Models\User;

/**
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
  <base href="<?= str_replace('index.php', '', $_SERVER['SCRIPT_NAME']) ?>" />
  <link rel="icon" href="./resources/images/favicon.svg" />
  <link rel="stylesheet" href="./resources/dist/minimal.css" />
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

  <script src="./resources/dist/minimal.js" defer></script>
</body>

</html>
