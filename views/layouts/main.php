<?php

/** @var App\Models\User $user */
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= $title ?> - HAJU</title>
  <link rel="icon" href="<?= asset('img/favicon.png') ?>" type="image/png" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,500;1,700&family=Rajdhani:wght@300;400;500;600;700&display=swap" />
  <link rel="stylesheet" href="<?= asset('css/metisMenu.css') ?>" />
  <link rel="stylesheet" href="<?= asset('css/bootstrap1.min.css') ?>" />
  <link rel="stylesheet" href="<?= asset('vendors/themefy_icon/themify-icons.css') ?>" />
  <link rel="stylesheet" href="<?= asset('css/style1.css') ?>" />
  <link rel="stylesheet" href="<?= asset('css/custom.css') ?>" />
</head>

<body>
  <nav class="sidebar">
    <div class="logo p-4 m-0 d-flex justify-content-between align-items-center">
      <img src="<?= asset('img/logo.png') ?>" />
      <div class="sidebar_close_icon d-flex align-items-center d-lg-none">
        <i class="ti-close"></i>
      </div>
    </div>
    <menu class="m-0 p-0" id="sidebar_menu">
      <li class="side_menu_title">
        <span>Panel de Administración</span>
      </li>
      <li class="mm-active">
        <a href="<?= route('/') ?>">
          <img src="<?= asset('img/menu-icon/1.svg') ?>" />
          <span>Inicio</span>
        </a>
      </li>
    </menu>
  </nav>
  <section class="main_content dashboard_part">
    <header class="header_iner d-flex justify-content-between align-items-center p-3">
      <button class="sidebar_icon d-lg-none">
        <i class="ti-menu"></i>
      </button>
      <div class="serach_field-area m-0">
        <!-- <form class="search_inner">
          <div class="search_field">
            <input required type="search" placeholder="Buscar...">
          </div>
          <button>
            <img src="<?= asset('img/icon/icon_search.svg') ?>" />
          </button>
        </form> -->
      </div>
      <div class="header_right d-flex justify-content-between align-items-center">
        <!-- <ul class="header_notification_warp d-flex align-items-center">
          <li>
            <a href="<?= route('/notificaciones') ?>">
              <img src="<?= asset('img/icon/bell.svg') ?>" />
            </a>
          </li>
        </ul> -->
        <div class="profile_info">
          <img src="<?= $user->avatar ?? asset('img/client_img.png') ?>" height="69" />
          <div class="profile_info_iner">
            <p><?= $user->speciality ?></p>
            <h5><?= "{$user->prefix?->value} {$user->getFullName()}" ?></h5>
            <div class="profile_info_details">
              <!-- <a href="<?= route('/perfil') ?>">
                Mi perfil
                <i class="ti-user"></i>
              </a> -->
              <!-- <a href="<?= route('/configuracion') ?>">
                Configuraciones
                <i class="ti-settings"></i>
              </a> -->
              <a href="<?= route('/salir') ?>">
                Cerrar sesión
                <i class="ti-shift-left"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </header>
    <!-- <div class="main_content_iner">
      <div class="container-fluid p-0">
        <div class="row justify-content-center">
          <?= $content ?>
        </div>
      </div>
    </div> -->
    <footer class="footer_part px-0 position-relative mt-0">
      <p class="footer_iner text-center py-3 mx-5">
        <?= date('Y') ?> © UPTM - Intregrantes
        <i class="ti-heart"></i>
        Daniel Mancilla, Franyer Sánchez, Jénifer Lázaro
      </p>
    </footer>
  </section>
  <script src="<?= asset('js/jquery1-3.4.1.min.js') ?>"></script>
  <script src="<?= asset('js/metisMenu.js') ?>"></script>
  <script src="<?= asset('js/custom.js') ?>"></script>
</body>

</html>
