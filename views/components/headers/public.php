<?php

declare(strict_types=1);

?>

<header class="bg-white py-2 sticky-top">
  <div class="container d-flex justify-content-between align-items-center">
    <a href="./">
      <?php Flight::render('components/hospital-logo') ?>
    </a>
    <menu class="p-0 m-0 nav align-items-center gap-3">
      <?php Flight::render('components/theme-toggler') ?>
      <?php Flight::render('components/headers/links-mobile') ?>
      <?php Flight::render('components/headers/links-desktop') ?>
    </menu>
  </div>
</header>

<?php Flight::render('components/modals/master-password') ?>
