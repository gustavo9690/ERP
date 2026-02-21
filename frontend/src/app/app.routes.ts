import { Routes } from '@angular/router';
import { AUTH_ROUTES } from './features/auth/auth.routes';
import { DASHBOARD_ROUTES } from './features/dashboard/dashboard.routes';
import { AuthLayoutComponent } from './layout/auth-layout/auth-layout.component';
import { MainLayoutComponent } from './layout/main-layout/main-layout.component';
import { EMPLEADO_ROUTES } from './features/empleado/empleado.routes';



export const routes: Routes = [

    { path: '', redirectTo: 'login', pathMatch: 'full' },

    // Auth Layout
    {
        path: 'login',
        component: AuthLayoutComponent,
        children: AUTH_ROUTES
    },

   
    // Main Layout
    {
        path: 'dashboard',
        component: MainLayoutComponent,
        children: [
            ...DASHBOARD_ROUTES
        ]
    },

    {
        path: 'empleado',
        component: MainLayoutComponent,
        children: [
            ...EMPLEADO_ROUTES
        ]
    }

];
