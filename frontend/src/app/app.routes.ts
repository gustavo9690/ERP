import { Routes } from '@angular/router';
import { AUTH_ROUTES } from './features/auth/auth.routes';
import { DASHBOARD_ROUTES } from './features/dashboard/dashboard.routes';
import { AuthLayoutComponent } from './layout/auth-layout/auth-layout.component';
import { MainLayoutComponent } from './layout/main-layout/main-layout.component';
import { authGuard } from './core/guards/auth.guard';
import { guestGuard } from './core/guards/guest.guard';
import { RRHH_ROUTES } from './features/rrhh/rrhh.routes';
import { SEGURIDAD_ROUTES } from './features/seguridad/seguridad.routes';



export const routes: Routes = [

    { path: '', redirectTo: 'login', pathMatch: 'full' },

    // 🔐 AUTH LAYOUT
    {
        path: '',
        component: AuthLayoutComponent,
        canActivate: [guestGuard],
        children: [
        {
            path: 'login',
            children: AUTH_ROUTES
        }
        ]
    },

   
     // 🏠 MAIN LAYOUT (PROTEGIDO)
    {
        path: '',
        component: MainLayoutComponent,
        canActivate: [authGuard],
        children: [

        {
            path: 'dashboard',
            children: DASHBOARD_ROUTES
        },

        {
            path: 'rrhh',
            children: RRHH_ROUTES
        },

        {
            path: 'seguridad',
            children: SEGURIDAD_ROUTES
        }

        ]
    },

    // 🚫 404
    {
        path: '**',
        redirectTo: 'dashboard'
    }


];
