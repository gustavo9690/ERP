import { Routes } from '@angular/router';

export const ROLES_ROUTES: Routes = [
  {
    path: '',
    redirectTo: 'lista',
    pathMatch: 'full'
  },
    {
    path: 'lista',
    data: { breadcrumb: 'Lista roles' },
    loadComponent: () =>
      import('./pages/roles-list/roles-list.component')
        .then(m => m.RolesListComponent)
  }

];
