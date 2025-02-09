<header class="bg-white py-2 sticky-top">
  <div class="container d-flex justify-content-between align-items-center">
    <a href="./">
      <?php renderComponent('hospital-logo') ?>
    </a>
    <menu class="p-0 m-0 nav align-items-center gap-3">
      <?php renderComponent('theme-toggler') ?>
      <?php renderComponent('headers/links-mobile') ?>
      <?php renderComponent('headers/links-desktop') ?>
    </menu>
  </div>
</header>

<?php renderComponent('modals/master-password') ?>
