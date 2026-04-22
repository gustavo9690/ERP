export const MENU_CONFIG = [
  {
    key: 'rrhh',
    label: 'RRHH',
    items: [
      {
        label: 'Empleados',
        children: [
          { label: 'Lista', route: '/rrhh/empleado/lista' },
          { label: 'Detalle', route: '/rrhh/empleado/detalle' }
        ]
      },
      {
        label: 'Asistencia',
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
        children: [
          { label: 'Lista', route: '/seguridad/roles/lista' }
        ]
      }
    ]
  },

  // 👇 NUEVO
  {
    key: 'configuracion',
    label: 'Configuración',
    items: [
      {
        label: 'Estructura',
        route: '/configuracion/estructura'
      }
    ]
  }
  
];