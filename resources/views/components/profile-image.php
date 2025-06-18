<?php

declare(strict_types=1);

use HAJU\Enums\IconPosition;
use HAJU\Models\User;

/**
 * @var User $user
 */

$links = [
  [
    'title' => 'Mi perfil',
    'href' => './perfil',
    'iconClass' => 'fa fa-user',
  ],
];

?>

<li class="dropdown">
  <Image
    loading="eager"
    width="50"
    height="50"
    class="dropdown-toggle rounded-circle object-fit-cover"
    src="<?= getUserAvatarUrl($user) ?>"
    alt="Foto de perfil del usuario"
    data-bs-toggle="dropdown"
    style="cursor: pointer" />
  <div class="dropdown-menu dropdown-menu-end pb-0 text-end">
    <small class="d-block fw-light dropdown-header"><?= $user->getParsedAppointment() ?></small>
    <strong class="d-block small px-3">
      <?= $user->instructionLevel->getName() ?>. <?= $user->getFullName() ?>
    </strong>
    <hr class="mb-0" />
    <menu class="p-0 m-0">
      <?php foreach ($links as $link) : ?>
        <a
          href="<?= $link['href'] ?>"
          class="dropdown-item d-flex gap-3 align-items-center justify-content-end py-3">
          <?= $link['title'] ?>
          <span class="<?= $link['iconClass'] ?>"></span>
        </a>
      <?php endforeach ?>
      <?php Flight::render('components/logout-link', [
        'class' => 'dropdown-item d-flex gap-3 align-items-center justify-content-end py-3',
        'iconPosition' => IconPosition::END,
      ]) ?>
    </menu>
  </div>
</li>
