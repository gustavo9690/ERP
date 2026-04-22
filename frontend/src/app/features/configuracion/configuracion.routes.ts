import { Routes } from '@angular/router';

export const CONFIGURACION_ROUTES: Routes = [
  {
    path: 'estructura',
    loadChildren: () =>
      import('./estructura/estructura.routes').then(m => m.ESTRUCTURA_ROUTES)
  }
];