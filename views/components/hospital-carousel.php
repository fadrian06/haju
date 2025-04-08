<?php

declare(strict_types=1);

$id = uniqid();

?>

<div
  id="<?= $id ?>"
  class="carousel slide carousel-fade"
  data-bs-ride="carousel">
  <div class="carousel-indicators">
    <?php foreach (range(0, 2) as $index) : ?>
      <button
        data-bs-target="#<?= $id ?>"
        data-bs-slide-to="<?= $index ?>"
        class="<?= $index ?: 'active' ?>">
      </button>
    <?php endforeach ?>
  </div>
  <div class="carousel-inner">
    <?php foreach (range(1, 3) as $index => $imageNumber) : ?>
      <div class="carousel-item <?= $index ?: 'active' ?>">
        <img
          loading="<?= $index ? 'eager' : 'lazy' ?>"
          src="./assets/img/hospital-exterior-<?= $imageNumber ?>.jpg"
          height="350"
          class="object-fit-cover w-100"
          alt='Exterior del hospital "Antonio José Uzcátegui"' />
        <div class="carousel-caption w-100 d-none d-md-block">
          <h5 class="d-none">Slide label</h5>
          <p class="d-none">
            Some representative placeholder content for the slide.
          </p>
        </div>
      </div>
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
