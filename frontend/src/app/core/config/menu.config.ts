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
    key: 'logistica',
    label: 'Logística',
    items: [
      {
        label: 'Productos',
       children: [
          { label: 'Lista', route: '/rrhh/empleado/lista' },
          { label: 'Detalle', route: '/rrhh/empleado/detalle' }
        ]
      },
      {
        label: 'Marcas',
        route: '/rrhh/empleado/detalle'
      }
    ]
  }
];