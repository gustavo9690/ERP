import { Routes } from '@angular/router';

export const EMPLEADO_ROUTES: Routes = [
    {
        path: '',
        loadComponent: () =>
        import('./pages/empleado-detalle/empleado-detalle.component')
            .then(m => m.EmpleadoDetalleComponent)
    },
   {
    path: 'detalle',
    loadComponent: () =>
      import('./pages/empleado-detalle/empleado-detalle.component')
        .then(m => m.EmpleadoDetalleComponent)
  },
  {
    path: 'lista',
    loadComponent: () =>
      import('./pages/empleado-lista/empleados-lista.component')
        .then(m => m.EmpleadoListaComponent)
  }

];
