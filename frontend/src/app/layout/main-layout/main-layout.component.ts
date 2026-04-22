import { ChangeDetectorRef, Component, OnDestroy, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterOutlet } from '@angular/router';
import { Subscription } from 'rxjs';

import { SidebarComponent } from '../../shared/components/sidebar/sidebar.component';
import { HeaderComponent } from '../../shared/components/header/header.component';
import { BreadcrumbComponent } from '../../shared/components/breadcrumb/breadcrumb.component';
import { ConfirmModalComponent } from '../../shared/components/confirm-modal/confirm-modal.component';

import { AuthService } from '../../core/services/auth.service';
import { SessionService } from '../../core/services/session.service';

@Component({
  selector: 'app-main-layout',
  standalone: true,
  imports: [
    CommonModule,
    RouterOutlet,
    SidebarComponent,
    HeaderComponent,
    BreadcrumbComponent,
    ConfirmModalComponent
  ],
  templateUrl: './main-layout.component.html',
  styleUrl: './main-layout.component.scss'
})
export class MainLayoutComponent implements OnInit, OnDestroy {

  user$;
  isSidebarOpen = true;

  showSessionPopup = false;
  sessionCountdown = 120;

  private subscriptions = new Subscription();

  breadcrumbs = [
    { label: 'Inicio', route: '/dashboard' },
    { label: 'RRHH', route: '/rrhh' },
    { label: 'Empleados' }
  ];

  constructor(
    private authService: AuthService,
    private sessionService: SessionService,
    private cdr: ChangeDetectorRef
  ) {
    this.user$ = this.authService.user$;
  }

  ngOnInit(): void {
    this.sessionService.stopMonitoring();
    this.sessionService.startMonitoring();

    this.subscriptions.add(
      this.sessionService.popupState$.subscribe((show: boolean) => {
        this.showSessionPopup = show;
        this.cdr.detectChanges();
      })
    );

    this.subscriptions.add(
      this.sessionService.countdown$.subscribe((seconds: number) => {
        this.sessionCountdown = seconds;
        this.cdr.detectChanges();
      })
    );
  }

  toggleSidebar(): void {
    this.isSidebarOpen = !this.isSidebarOpen;
  }

  continuarSesion(): void {
    this.sessionService.continueSession();
  }

  cerrarSesion(): void {
    this.sessionService.logoutNow();
  }

  cerrarPopup(): void {
    this.sessionService.logoutNow();
  }

  ngOnDestroy(): void {
    this.subscriptions.unsubscribe();
    this.sessionService.stopMonitoring();
  }
}