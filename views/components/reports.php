<section class="mb-4 d-flex align-items-center">
  <h2>Reportes - </h2>
  <i class="ti-write ms-2 h2"></i>
  <span class="border border-dark flex-grow-1 ms-2 h2"></span>
</section>

<ul class="list-unstyled row row-cols-sm-2 row-cols-md-3">
  <li class="mb-4 d-flex align-items-stretch">
    <article class="card card-body text-center">
      <picture class="p-3 pt-1 text-success w-50 mx-auto" data-bs-toggle="tooltip" title="Sistema de Información para la Salud ~ Epidemiología">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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
          'labelStyle' => 'left: 0'
        ]);

        ?>

        <button class="btn btn-sm btn-primary">Generar</button>
      </form>
    </article>
  </li>
  <li class="mb-4 d-flex align-items-stretch">
    <article class="card card-body text-center">
      <picture
        class="p-3 pt-1 text-success w-50 mx-auto"
        data-bs-toggle="tooltip"
        title="Sistema de Información para la Salud ~ Epidemiología">
        <?xml version="1.0" encoding="iso-8859-1"?>
        <svg viewBox="0 0 460 460" xml:space="preserve">
          <g>
            <path style="fill:#1398AD;" d="M230,80c-7.288,0-14.115-1.959-20-5.365v47.184c6.486-1.191,13.169-1.819,20-1.819
            s13.514,0.628,20,1.819V74.635C244.115,78.041,237.288,80,230,80z" />
            <path style="fill:#1398AD;" d="M123.934,123.934c-5.154,5.154-11.366,8.596-17.936,10.349l33.364,33.364
            c3.744-5.429,8.026-10.599,12.856-15.429c4.83-4.83,10-9.112,15.429-12.856l-33.364-33.364
            C132.53,112.568,129.088,118.78,123.934,123.934z" />
            <circle style="fill:#3FC3D8;" cx="95.65" cy="95.65" r="40" />
            <path style="fill:#1398AD;" d="M80,230c0,7.288-1.959,14.115-5.365,20h47.184c-1.191-6.486-1.819-13.169-1.819-20
            s0.628-13.514,1.819-20H74.635C78.041,215.885,80,222.712,80,230z" />
            <circle style="fill:#3FC3D8;" cx="40" cy="230" r="40" />
            <path style="fill:#1398AD;" d="M123.934,336.066c5.154,5.154,8.596,11.366,10.349,17.936l33.364-33.364
            c-5.429-3.744-10.599-8.026-15.429-12.856c-4.83-4.83-9.112-10-12.856-15.429l-33.364,33.364
            C112.568,327.47,118.78,330.912,123.934,336.066z" />
            <circle style="fill:#3FC3D8;" cx="95.65" cy="364.35" r="40" />
            <path style="fill:#0B389C;" d="M230,380c7.288,0,14.115,1.959,20,5.365v-47.184c-6.486,1.191-13.169,1.819-20,1.819
            s-13.514-0.628-20-1.819v47.184C215.885,381.959,222.712,380,230,380z" />
            <path style="fill:#0B389C;" d="M336.066,336.066c5.154-5.154,11.366-8.596,17.936-10.349l-33.364-33.364
            c-3.744,5.429-8.026,10.599-12.856,15.429c-4.83,4.83-10,9.112-15.429,12.856l33.364,33.364
            C327.47,347.432,330.912,341.22,336.066,336.066z" />
            <circle style="fill:#1398AD;" cx="364.35" cy="364.35" r="40" />
            <path style="fill:#0B389C;" d="M380,230c0-7.288,1.959-14.115,5.365-20h-47.184c1.191,6.486,1.819,13.169,1.819,20
            s-0.628,13.514-1.819,20h47.184C381.959,244.115,380,237.288,380,230z" />
            <circle style="fill:#1398AD;" cx="420" cy="230" r="40" />
            <path style="fill:#0B389C;" d="M336.066,123.934c-5.154-5.154-8.596-11.366-10.349-17.936l-33.364,33.364
            c5.429,3.744,10.599,8.026,15.429,12.856c4.83,4.83,9.112,10,12.856,15.429l33.364-33.364
            C347.432,132.53,341.22,129.088,336.066,123.934z" />
            <circle style="fill:#1398AD;" cx="364.35" cy="95.65" r="40" />
            <path style="fill:#EF9E8F;" d="M190,230c0-22.091,17.909-40,40-40v-70c-60.751,0-110,49.249-110,110s49.249,110,110,110v-70
            C207.909,270,190,252.091,190,230z" />
            <path style="fill:#F57B71;" d="M230,120v70c22.091,0,40,17.909,40,40s-17.909,40-40,40v70c60.751,0,110-49.249,110-110
            S290.751,120,230,120z" />
            <path style="fill:#3FC3D8;" d="M190,40c0,22.091,17.909,40,40,40V0C207.909,0,190,17.909,190,40z" />
            <path style="fill:#1398AD;" d="M270,40c0-22.091-17.909-40-40-40v80C252.091,80,270,62.091,270,40z" />
            <path style="fill:#1398AD;" d="M270,420c0-22.091-17.909-40-40-40v80C252.091,460,270,442.091,270,420z" />
            <path style="fill:#3FC3D8;" d="M190,420c0,22.091,17.909,40,40,40v-80C207.909,380,190,397.909,190,420z" />
            <path style="fill:#EF8CA7;" d="M270,230c0-22.091-17.909-40-40-40v80C252.091,270,270,252.091,270,230z" />
            <path style="fill:#FFFFFF;" d="M190,230c0,22.091,17.909,40,40,40v-80C207.909,190,190,207.909,190,230z" />
          </g>
        </svg>
      </picture>
      <h4
        data-bs-toggle="tooltip"
        title="Sistema de Información para la Salud ~ Epidemiología">
        SIS-4 ~ EPI-15
      </h4>
      <small class="text-muted">
        Consolidado Mensual Morbilidad Registrada Por Enfermedades, Aparatos Y
        Sistemas
      </small>
      <form action="./reportes/epi-15" target="_blank" class="mt-3">
        <?php

        render('components/input-group', [
          'type' => 'month',
          'name' => 'fecha',
          'placeholder' => 'Mes/Año',
          'cols' => 12,
          'pattern' => '\d{4}-\d{2}',
          'title' => 'La fecha debe tener el formato AAAA-MM',
          'labelStyle' => 'left: 0'
        ]);

        ?>

        <button class="btn btn-sm btn-primary">Generar</button>
      </form>
    </article>
  </li>

</ul>
