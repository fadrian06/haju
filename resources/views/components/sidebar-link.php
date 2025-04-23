<?php

declare(strict_types=1);

/**
 * @var ?string $iconClass
 * @var ?string $iconSrc
 * @var string $title
 * @var array{iconClass?: string, href: string, title: string, isActive: bool, target?: string, show?: bool}[] $subItems
 */
$show ??= true;
$isActive ??= false;
$href ??= '';
$iconClass ??= '';
$iconSrc ??= '';
$subItems ??= [];

if (!$show) {
  return;
}

?>

<li class="<?= !$isActive ?: 'mm-active' ?>">
  <?php if ($subItems) : ?>
    <a
      href="#"
      class="btn rounded-0 has-arrow d-flex align-items-center gap-3"
      :class="`btn-${theme}`"
      @click="$el.classList.toggle('active')">
      <?php if ($iconSrc) : ?>
        <img src="<?= $iconSrc ?>" class="object-fit-contain" style="height: 1.5em; width: 1.5em" />
      <?php endif ?>

      <?php if ($iconClass) : ?>
        <span class="<?= $iconClass ?>" style="font-size: 1.5em"></span>
      <?php endif ?>

      <?= $title ?>
    </a>
    <ul class="list-unstyled mm-collapse">
      <?php foreach ($subItems as $subItem) : ?>
        <?php if (array_key_exists('show', $subItem) && !$subItem['show']) : ?>
          <?php continue ?>
        <?php endif ?>
        <li class="<?= !$subItem['isActive'] ?: 'mm-active' ?>">
          <a
            class="btn rounded-0 d-flex align-items-center gap-3"
            :class="`<?= $subItem['isActive'] ? 'btn-primary' : 'btn-${theme}' ?>`"
            href="<?= $subItem['href'] ?>"
            <?= !$subItem['isActive'] ?: 'data-bs-toggle="modal"' ?>
            data-bs-target="<?= $subItem['target'] ?? '' ?>">
            <?php if (array_key_exists('iconClass', $subItem)) : ?>
              <span class="<?= $subItem['iconClass'] ?>"></span>
            <?php endif ?>
            <?= $subItem['title'] ?>
          </a>
        </li>
      <?php endforeach ?>
    </ul>
  <?php else : ?>
    <a
      class="btn rounded-0 d-flex align-items-center gap-3"
      :class="`<?= $isActive ? 'btn-primary' : 'btn-${theme}' ?>`"
      href="<?= $href ?>">
      <?php if ($iconSrc) : ?>
        <img src="<?= $iconSrc ?>" class="object-fit-contain" style="height: 1.5em; width: 1.5em" />
      <?php endif ?>

      <?php if ($iconClass) : ?>
        <span class="<?= $iconClass ?>" style="font-size: 1.5em"></span>
      <?php endif ?>
      <?= $title ?>
    </a>
  <?php endif ?>
</li>
