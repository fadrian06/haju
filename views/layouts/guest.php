<?php

use Leaf\Http\Session;

?>

<!doctype html>
<html
  x-data="{
    theme: `<?= Session::get('theme', 'light') ?>`,

    setTheme(theme = 'light') {
      this.theme = theme
      fetch(`./api/preferencias/tema/${theme}`)
    }
  }"
  :data-bs-theme="theme">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width" />
  <title><?= $title ?> - HAJU</title>
  <base href="<?= str_replace('index.php', '', $_SERVER['SCRIPT_NAME']) ?>" />
  <link rel="icon" href="./assets/img/favicon.svg" />
  <link rel="stylesheet" href="./assets/dist/guest.css" />
</head>

<body class="pb-5">
  <?php renderComponent('headers/public') ?>
  <?= $content ?>
  <?php renderComponent('footer') ?>
  <script src="./assets/dist/guest.js"></script>
</body>

</html>
