<?php

// phpcs:ignore Generic.Files.LineLength.MaxExceeded
$iframeSrc = 'https://www.openstreetmap.org/export/embed.html?bbox=-71.28103494644166%2C8.965167775885362%2C-71.27273082733156%2C8.969883750535764&amp;layer=mapnik&amp;marker=8.967525770867361%2C-71.2768828868866';

?>

<img
  src="./resources/images/hospital-location.png"
  alt='Ubicación del Hospital "José Antonio Uzcátegui"'
  class="d-none object-fit-contain w-100" />

<iframe
  :style="theme === 'dark' && 'filter: invert(1)'"
  loading="eager"
  height="350"
  src="<?= $iframeSrc ?>"
  title='Ubicación del Hospital "José Antonio Uzcátegui"'
  class="w-100">
</iframe>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    if (!navigator.onLine) {
      const image = document.querySelector(
        'img[alt^="Ubicación del Hospital"]',
      );

      const iframe = document.querySelector(
        'iframe[title^="Ubicación del Hospital"]',
      );

      image?.classList.remove('d-none');
      iframe?.classList.add('d-none');
    }
  });
</script>
