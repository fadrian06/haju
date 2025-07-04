<?php



?>

<header class="py-2 sticky-top" :class="`text-bg-${theme}`">
  <div class="container d-flex justify-content-between align-items-center">
    <a href="./">
      <?php Flight::render('components/hospital-logo') ?>
    </a>
    <menu class="p-0 m-0 nav align-items-center gap-3">
      <?php Flight::render('components/theme-toggler') ?>
      <?php Flight::render('components/headers/links-desktop') ?>
      <?php Flight::render('components/headers/links-mobile') ?>
    </menu>
  </div>
</header>

<?php Flight::render('components/modals/master-password') ?>
