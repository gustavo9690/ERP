export const MENU_CONFIG = [

  {
    key: 'rrhh',
    label: 'RRHH',

    items: [

      {
        label: 'Empleados',
        icon: 'empleados',

        children: [
          {
            label: 'Lista',
            route: '/rrhh/empleado/lista'
          },
          {
            label: 'Detalle',
            route: '/rrhh/empleado/detalle'
          }
        ]
      },

      {
        label: 'Asistencia',
        icon: 'asistencia',
        route: '/rrhh/asistencia'
      }

    ]
  },

  {
    key: 'seguridad',
    label: 'Seguridad',

    items: [

      {
        label: 'Roles',
        icon: 'roles',

        children: [
          {
            label: 'Lista',
            route: '/seguridad/roles/lista'
          }
        ]
      }

    ]
  },

  {
    key: 'configuracion',
    label: 'Configuración',

    items: [

      {
        label: 'Estructura',
        icon: 'estructura',
        route: '/configuracion/estructura'
      }

    ]
  }

];