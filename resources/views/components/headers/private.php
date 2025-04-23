<?php

declare(strict_types=1);

?>

<header class="navbar px-3 sticky-top" :class="`text-bg-${theme}`">
  <button
    data-bs-toggle="offcanvas"
    data-bs-target="#sidebar"
    class="border-0 btn d-lg-none fa fa-bars-staggered fa-2x"
    @mouseenter="$el.classList.add('fa-beat')"
    @mouseleave="$el.classList.remove('fa-beat')">
  </button>
  <?php Flight::render('components/department-switcher') ?>
  <ul
    class="list-group list-unstyled list-group-horizontal gap-3 align-items-center"
  >
    <?php Flight::render('components/theme-toggler') ?>
    <?php Flight::render('components/profile-image') ?>
  </ul>
</header>
