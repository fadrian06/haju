<?php

declare(strict_types=1);

use flight\Container;
use Leaf\Http\Session;

$session = Container::getInstance()->get(Session::class);
$error = isset($error) ? strval($error) : null;
$message = isset($message) ? strval($message) : null;

?>

<!doctype html>
<html
  lang="es"
  x-data="{
    theme: `<?= $session->get('theme', 'light') ?>`,

    setTheme(theme = 'light') {
      this.theme = theme;
      fetch(`./api/preferencias/tema/${theme}`);
    },
  }"
  data-bs-theme="<?= $session->get('theme', 'light') ?>"
  :data-bs-theme="theme">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width" />
  <title><?= $title ?> - HAJU</title>
  <base href="<?= str_replace('index.php', '', $_SERVER['SCRIPT_NAME']) ?>" />
  <link rel="icon" href="./resources/images/favicon.svg" />
  <link rel="stylesheet" href="./assets/dist/guest.css" />
</head>

<body
  class="overflow-y-scroll min-vh-100 d-grid"
  style="grid-template-rows: auto 1fr auto">
  <?php Flight::render('components/headers/public') ?>
  <?= $content ?>
  <?php Flight::render('components/footer') ?>

  <script src="./assets/dist/guest.js"></script>

  <script>
    <?php if ($error): ?>
      customSwal.fire({
        title: '<?= $error ?>',
        icon: 'error',
      })
    <?php elseif ($message): ?>
      customSwal.fire({
        title: '<?= $message ?>',
        icon: 'success',
      })
    <?php endif ?>
  </script>
</body>

</html>
