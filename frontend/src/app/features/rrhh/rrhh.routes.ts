import { Routes } from '@angular/router';

export const RRHH_ROUTES: Routes = [
  {
    path: '',
    data: { breadcrumb: 'RRHH' },
    children: [
      {
        path: 'empleado',
        data: { breadcrumb: 'Empleados' },
        loadChildren: () =>
          import('./empleado/empleado.routes')
            .then(m => m.EMPLEADO_ROUTES)
      },
      {
        path: '',
        data: { breadcrumb: 'Dashboard' },
        loadChildren: () =>
          import('./empleado/empleado.routes')
            .then(m => m.EMPLEADO_ROUTES)
      }
    ]
  }
];