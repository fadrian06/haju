<?php

declare(strict_types=1);

use Leaf\Http\Session;

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
  <title>Design System</title>
  <base href="<?= str_replace('index.php', '', $_SERVER['SCRIPT_NAME']) ?>" />
  <link rel="stylesheet" href="./resources/dist/design-system.css" />
</head>

<body>
  <?php Flight::render('components/headers/public') ?>



  <script src="./resources/dist/design-system.js" defer></script>
</body>

</html>
