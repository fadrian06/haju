<?php

use App\Models\User;
use PharIo\Manifest\Url;

/**
 * @var string $root
 * @var string $title
 * @var string $content
 * @var ?User $user
 * @var ?string $error
 * @var ?string $message
 */

if (isset($user)) {
  $user->profileImagePath = new Url(urldecode($user->profileImagePath->asString()));
}

?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= $title ?> - HAJU</title>
  <?php render('components/open-graph-metas') ?>
  <base href="<?= $root ?>/" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,500;1,700&family=Rajdhani:wght@300;400;500;600;700&display=swap" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
  <link rel="icon" href="./assets/img/logo-mini.png" />
  <link rel="stylesheet" href="./assets/vendors/themefy_icon/themify-icons.css" />
  <link rel="stylesheet" href="./assets/css/reset.css" />
  <link rel="stylesheet" href="./assets/css/utils.css" />
  <link rel="stylesheet" href="./assets/css/components/header.css" />
  <link rel="stylesheet" href="./assets/css/components/modal.css" />
  <link rel="stylesheet" href="./assets/css/components/btn.css" />
  <link rel="stylesheet" href="./assets/css/components/footer.css" />
  <link rel="stylesheet" href="./assets/css/components/main.css" />
  <link rel="stylesheet" href="./assets/css/components/modal.css" />
  <link rel="stylesheet" href="./assets/css/components/box.css" />
  <link rel="stylesheet" href="./assets/vendors/sweetalert2/default.min.css" />
  <link rel="stylesheet" href="./assets/css/custom.css" />
</head>

<body class="pb-4" style="display: grid; grid-template-rows: auto 1fr auto; min-height: 100vh">
  <header class="d-flex header_iner align-items-center py-0" style="height: 60px">
    <img src="./assets/img/logo.png" height="50" data-bs-toggle="tooltip" title='Hospital "José Antonio Uzcátegui"' />
    <nav class="header_right">
      <ul class="header_notification_warp d-flex align-items-center mx-0">
        <?php if (isActive('/departamento/seleccionar')): ?>
          <li class="d-none d-md-block">
            <a href="./salir">Cerrar sesión</a>
          </li>
        <?php endif ?>
      </ul>
    </nav>
  </header>
  <main class="main_content p-2 px-md-5 py-md-0">
    <div class="main_content_iner mx-0 my-4 white_box row justify-content-center">
      <?= $content ?>
    </div>
  </main>
  <?php render('components/footer') ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="./assets/vendors/sweetalert2/sweetalert2.min.js"></script>
  <script>
    for (const tooltipTriggerEl of document.querySelectorAll('[data-bs-toggle="tooltip"]')) {
      new bootstrap.Tooltip(tooltipTriggerEl)
    }

    const swal = Swal.mixin({
      // toast: true,
      // position: 'top-right',
      showCloseButton: true,
      showConfirmButton: false
    })

    <?php if ($error): ?>
      swal.fire({
        title: '<?= $error ?>',
        icon: 'error'
      })
    <?php elseif ($message): ?>
      swal.fire({
        title: '<?= $message ?>',
        icon: 'success'
      })
    <?php endif ?>
  </script>
</body>

</html>