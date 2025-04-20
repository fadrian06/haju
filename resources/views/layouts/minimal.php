<?php

declare(strict_types=1);

$title = isset($title) ? strval($title) : throw new Error('Title not set');

$content = isset($content)
  ? strval($content)
  : throw new Error('Content not set');

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width" />
  <title><?= $title ?> - HAJU</title>
  <base href="<?= str_replace('index.php', '', $_SERVER['SCRIPT_NAME']) ?>" />
  <?php Flight::render('components/open-graph-metas') ?>
  <link rel="icon" href="./assets/img/logo-mini.png" />

  <link
    rel="stylesheet"
    href="./vendor/twbs/bootstrap/dist/css/bootstrap.min.css" />

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

  <script src="./vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js">
    // ...
  </script>

  <script>
    const tooltipTriggers = document.querySelectorAll(
      '[data-bs-toggle="tooltip"]'
    )

    for (const tooltipTrigger of tooltipTriggers) {
      new bootstrap.Tooltip(tooltipTrigger)
    }
  </script>
</body>

</html>
