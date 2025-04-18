<?php

declare(strict_types=1);

use HAJU\Enums\ToastPosition;
use Leaf\Flash;
use Leaf\Http\Session;

$title = isset($title) ? strval($title) : throw new Error('Title not set');

$content = isset($content)
  ? strval($content)
  : throw new Error('Content not set');

$error = Flash::display('error');

?>

<!doctype html>
<html
  lang="es"
  x-data="{
    theme: `<?= Session::get('theme', 'light') ?>`,

    setTheme(theme = 'light') {
      this.theme = theme;
      fetch(`./api/preferencias/tema/${theme}`);
    },
  }"
  data-bs-theme="<?= Session::get('theme', 'light') ?>"
  :data-bs-theme="theme">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width" />
  <title><?= $title ?> - <?= $_ENV['APP_NAME'] ?? 'HAJU' ?></title>
  <base href="<?= str_replace('index.php', '', $_SERVER['SCRIPT_NAME']) ?>" />
  <link rel="icon" href="./resources/images/favicon.svg" />
  <link rel="stylesheet" href="./resources/dist/guest.css" />
  <script src="./resources/dist/guest.js" defer></script>

  <style>
    body {
      grid-template-rows: auto 1fr auto;
    }
  </style>
</head>

<body class="overflow-y-scroll min-vh-100 d-grid">
  <?php Flight::render('components/headers/public') ?>
  <?= $content ?>
  <?php Flight::render('components/footer') ?>

  <?php Flight::render(
    'components/toasts',
    [
      'errors' => $error ? [$error] : [],
      'success' => Flash::display('success'),
      'position' => ToastPosition::BOTTOM_RIGHT,
    ]
  ) ?>
</body>

</html>
