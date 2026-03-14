import { Routes } from '@angular/router';

export const SEGURIDAD_ROUTES: Routes = [
  {
    path: '',
    data: { breadcrumb: 'Seguridad' },
    children: [
      {
        path: 'roles',
        data: { breadcrumb: 'Roles' },
        loadChildren: () =>
          import('./roles/roles.routes')
            .then(m => m.ROLES_ROUTES)
      }
    ]
  }
];