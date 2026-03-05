import { Routes } from '@angular/router';

export const RRHH_ROUTES: Routes = [
  {
    path: '',
    children: [
      {
        path: 'empleado',
        loadChildren: () =>
          import('./empleado/empleado.routes')
            .then(m => m.EMPLEADO_ROUTES)
      },
      {
         path: '',
        loadChildren: () =>
          import('./empleado/empleado.routes')
            .then(m => m.EMPLEADO_ROUTES)
    },
    ]
  }
];