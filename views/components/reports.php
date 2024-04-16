<section class="mb-4 d-flex align-items-center">
  <h2>Reportes - </h2>
  <i class="ti-write ms-2 h2"></i>
  <span class="border border-dark flex-grow-1 ms-2 h2"></span>
</section>

<ul class="list-unstyled row row-cols-sm-2 row-cols-md-3">
  <li class="mb-4 d-flex align-items-stretch">
    <article class="card card-body text-center">
      <picture class="p-3 pt-1 text-success w-50 mx-auto" data-bs-toggle="tooltip" title="Sistema de Información para la Salud ~ Epidemiología">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <path d="M17 12a5 5 0 1 0 -5 5" />
          <path d="M12 7v-4" />
          <path d="M11 3h2" />
          <path d="M15.536 8.464l2.828 -2.828" />
          <path d="M17.657 4.929l1.414 1.414" />
          <path d="M17 12h4" />
          <path d="M21 11v2" />
          <path d="M12 17v4" />
          <path d="M13 21h-2" />
          <path d="M8.465 15.536l-2.829 2.828" />
          <path d="M6.343 19.071l-1.413 -1.414" />
          <path d="M7 12h-4" />
          <path d="M3 13v-2" />
          <path d="M8.464 8.464l-2.828 -2.828" />
          <path d="M4.929 6.343l1.414 -1.413" />
          <path d="M17.5 17.5m-2.5 0a2.5 2.5 0 1 0 5 0a2.5 2.5 0 1 0 -5 0" />
          <path d="M19.5 19.5l2.5 2.5" />
        </svg>
        <!-- <img class="img-fluid" src="./assets/img/icons/cap.svg" style="height: 75px; object-fit: contain;" /> -->
      </picture>
      <h4 data-bs-toggle="tooltip" title="Sistema de Información para la Salud ~ Epidemiología">SIS-3 ~ EPI-11</h4>
      <small class="text-muted">
        Tabulador diario de morbilidad
      </small>
      <form action="./reportes/epi-11" target="_blank" class="mt-3">
        <?php

        render('components/input-group', [
          'type' => 'month',
          'name' => 'fecha',
          'placeholder' => 'Mes/Año',
          'cols' => 12,
          'pattern' => '\d{4}-\d{2}',
          'title' => 'La fecha debe tener el formato AAAA-MM',
        ]);

        ?>

        <button class="btn btn-sm btn-primary">Generar</button>
      </form>
    </article>
  </li>
</ul>
