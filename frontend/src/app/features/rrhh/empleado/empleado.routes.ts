import { Routes } from '@angular/router';

export const EMPLEADO_ROUTES: Routes = [
    {
        path: '',
        data: { breadcrumb: 'Detalle empleado' },
        loadComponent: () =>
        import('./pages/empleado-detalle/empleado-detalle.component')
            .then(m => m.EmpleadoDetalleComponent)
    },
   {
    path: 'detalle',
    data: { breadcrumb: 'Detalle empleado' },
    loadComponent: () =>
      import('./pages/empleado-detalle/empleado-detalle.component')
        .then(m => m.EmpleadoDetalleComponent)
  },
  {
    path: 'lista',
    data: { breadcrumb: 'Reporte empleado' },
    loadComponent: () =>
      import('./pages/empleado-lista/empleados-lista.component')
        .then(m => m.EmpleadoListaComponent)
  }

];
