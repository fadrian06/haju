<?php

use HAJU\Enums\Appointment;
use HAJU\Models\Department;
use HAJU\Models\User;

/**
 * @var Department $department
 * @var User $user
 */

?>

<menu class="list-unstyled metismenu" id="sidebar-navigation">
  <h2 class="h3">Panel de administración</h2>
  <?php

  Flight::render('components/sidebar-link', [
    'href' => './',
    'iconClass' => 'fa fa-home',
    'title' => 'Inicio',
    'isActive' => isActive('/'),
  ]);

  Flight::render('components/sidebar-link', [
    'title' => 'Pacientes',
    'iconClass' => 'fa fa-hospital-user',
    'isActive' => isActive('/pacientes', '/consultas/registrar', '/hospitalizaciones/registrar'),
    'subItems' => [
      [
        'href' => './pacientes',
        'iconClass' => 'fa fa-list',
        'title' => 'Listado',
        'isActive' => isActive('/pacientes'),
      ],
      [
        'href' => './pacientes#registrar',
        'isActive' => isActive('/pacientes'),
        'target' => '#registrar',
        'iconClass' => 'fa fa-plus',
        'title' => 'Registrar paciente',
        'show' => !$department->isStatistics(),
      ],
      [
        'href' => './consultas/registrar',
        'isActive' => isActive('/consultas/registrar'),
        'iconClass' => 'fa fa-plus',
        'title' => 'Registrar consulta',
        'show' => !$department->isStatistics(),
      ],
      [
        'href' => './hospitalizaciones/registrar',
        'isActive' => isActive('/hospitalizaciones/registrar'),
        'iconClass' => 'fa fa-plus',
        'title' => 'Registrar hospitalización',
        'show' => !$department->isStatistics(),
      ],
    ],
  ]);

  Flight::render('components/sidebar-link', [
    'href' => './hospitalizaciones',
    'iconClass' => 'fa fa-bed-pulse',
    'title' => 'Hospitalizaciones',
    'isActive' => isActive('/hospitalizaciones'),
  ]);

  Flight::render('components/sidebar-link', [
    'href' => './consultas',
    'iconClass' => 'fa fa-stethoscope',
    'title' => 'Consultas',
    'isActive' => isActive('/consultas'),
  ]);

  Flight::render('components/sidebar-link', [
    'iconClass' => 'fa fa-user-md',
    'title' => 'Doctores',
    'isActive' => isActive('/doctores'),
    'show' => $user->appointment->isHigherThan(Appointment::Coordinator),
    'subItems' => [
      [
        'href' => './doctores',
        'iconClass' => 'fa fa-list',
        'title' => 'Listado',
        'isActive' => isActive('/doctores'),
      ],
      [
        'href' => './doctores#registrar',
        'isActive' => isActive('/doctores'),
        'target' => '#registrar',
        'iconClass' => 'fa fa-plus',
        'title' => 'Registrar doctor',
      ],
    ],
  ]);

  Flight::render('components/sidebar-link', [
    'iconClass' => 'fa fa-users',
    'title' => 'Usuarios',
    'isActive' => isActive('/usuarios'),
    'subItems' => [
      [
        'href' => './usuarios',
        'iconClass' => 'fa fa-list',
        'title' => 'Listado',
        'isActive' => isActive('/usuarios'),
      ],
      [
        'href' => './usuarios#registrar',
        'isActive' => isActive('/usuarios'),
        'target' => '#registrar',
        'iconClass' => 'fa fa-plus',
        'title' => 'Registrar usuario',
      ],
    ],
  ]);

  Flight::render('components/sidebar-link', [
    'href' => './departamentos',
    'iconClass' => 'fa fa-hospital',
    'title' => 'Departamentos',
    'isActive' => isActive('/departamentos'),
    'show' => $user->appointment->isDirector(),
  ]);

  Flight::render('components/sidebar-link', [
    'title' => 'Configuraciones',
    'iconClass' => 'fa fa-gears',
    'isActive' => isActive(
      '/configuracion',
      '/configuracion/institucion',
      '/logs',
      '/configuracion/permisos',
      '/configuracion/respaldo-restauracion',
      '/configuracion/causas-de-consulta'
    ),
    'show' => $user->appointment->isHigherThan(Appointment::Coordinator),
    'subItems' => [
      [
        'href' => './configuracion/institucion',
        'title' => 'Institución',
        'iconClass' => 'fa fa-hospital',
        'isActive' => isActive('/configuracion/institucion'),
        'show' => $user->appointment->isDirector(),
      ],
      [
        'href' => './logs',
        'title' => 'Ingresos de usuarios',
        'iconClass' => 'fa fa-history',
        'isActive' => isActive('/logs'),
        'show' => $user->appointment->isDirector(),
      ],
      [
        'href' => './configuracion/permisos',
        'title' => 'Asignar departamentos',
        'iconClass' => 'fa fa-user-tag',
        'isActive' => isActive('/configuracion/permisos'),
      ],
      [
        'href' => './configuracion/respaldo-restauracion',
        'title' => 'Respaldo y restauración',
        'iconClass' => 'fa fa-download',
        'isActive' => isActive('/configuracion/respaldo-restauracion'),
        'show' => $user->appointment->isDirector() || $user->hasDepartment('Estadística'),
      ],
      [
        'href' => './configuracion/causas-de-consulta',
        'title' => 'Configurar causas de consultas',
        'iconClass' => 'fa fa-bookmark',
        'isActive' => isActive('/configuracion/causas-de-consulta'),
        'show' => $user->appointment->isHigherThan(Appointment::Coordinator),
      ],
    ],
  ]);

  ?>
</menu>
