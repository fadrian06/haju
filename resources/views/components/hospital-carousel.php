<?php

declare(strict_types=1);

$id = uniqid();

$hospitalImages = [
  [
    'src' => './assets/img/hospital-exterior-1.jpg',
    'title' => '',
    'description' => '',
  ],
  [
    'src' => './assets/img/hospital-exterior-2.jpg',
    'title' => '',
    'description' => '',
  ],
  [
    'src' => './assets/img/hospital-exterior-3.jpg',
    'title' => '',
    'description' => '',
  ],
];

?>

<div
  id="<?= $id ?>"
  class="carousel slide carousel-fade"
  data-bs-ride="carousel">
  <div class="carousel-indicators">
    <?php foreach (array_keys($hospitalImages) as $index) : ?>
      <button
        data-bs-target="#<?= $id ?>"
        data-bs-slide-to="<?= $index ?>"
        class="<?= $index ?: 'active' ?>">
      </button>
    <?php endforeach ?>
  </div>
  <div class="carousel-inner">
    <?php foreach ($hospitalImages as $index => $image) : ?>
      <figure class="carousel-item <?= $index ?: 'active' ?>">
        <img
          loading="<?= $index ? 'lazy' : 'eager' ?>"
          src="<?= $image['src'] ?>"
          height="350"
          class="object-fit-cover w-100"
          alt="<?= $image['title'] ?>" />
        <figcaption class="carousel-caption w-100">
          <h5><?= $image['title'] ?></h5>
          <p><?= $image['description'] ?></p>
        </figcaption>
      </figure>
    <?php endforeach ?>
  </div>
  <button
    class="carousel-control-prev"
    data-bs-target="#<?= $id ?>"
    data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button
    class="carousel-control-next"
    data-bs-target="#<?= $id ?>"
    data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>
