import { Component, CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterOutlet } from '@angular/router';
import { SidebarComponent } from '../../shared/components/sidebar/sidebar.component';
import { HeaderComponent } from '../../shared/components/header/header.component';
import { AuthService } from '../../core/services/auth.service';
import { BreadcrumbComponent } from "../../shared/components/breadcrumb/breadcrumb.component";


@Component({
  selector: 'app-main-layout',
  standalone: true,
  imports: [
    CommonModule,
    RouterOutlet,
    SidebarComponent,
    HeaderComponent,
    BreadcrumbComponent
],
  templateUrl: './main-layout.component.html',
  styleUrl: './main-layout.component.scss',
  schemas: [CUSTOM_ELEMENTS_SCHEMA]
})
export class MainLayoutComponent {
  
  user$; // se define primero sin valor

  breadcrumbs = [
    { label: 'Inicio', route: '/dashboard' },
    { label: 'RRHH', route: '/rrhh' },
    { label: 'Empleados' }
  ];

  constructor(private authService: AuthService) {
    this.user$ = this.authService.user$; // aquí authService ya está inicializado
  }
  
  isSidebarOpen = true;

  toggleSidebar() {
    this.isSidebarOpen = !this.isSidebarOpen;
  }

  
}