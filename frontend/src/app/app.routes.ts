import { Routes } from '@angular/router';
import { AUTH_ROUTES } from './features/auth/auth.routes';
import { AuthLayoutComponent } from './layout/auth-layout/auth-layout.component';


export const routes: Routes = [

    { path: '', redirectTo: 'login', pathMatch: 'full' },

    // Auth Layout
    {
        path: '',
        component: AuthLayoutComponent,
        children: AUTH_ROUTES
    }

   
    // Main Layout

];
