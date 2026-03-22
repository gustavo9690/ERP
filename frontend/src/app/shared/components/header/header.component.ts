import { CommonModule } from '@angular/common';
import { ChangeDetectorRef, Component, EventEmitter, HostListener, Input, Output } from '@angular/core';
import { NavigationEnd, Router, RouterLink, RouterLinkActive } from '@angular/router';
import { MENU_CONFIG } from '../../../core/config/menu.config';
import { filter } from 'rxjs/operators';
import { AuthService } from '../../../core/services/auth.service';

@Component({
  selector: 'app-header',
  standalone: true,
  imports: [
    CommonModule,
    RouterLink,
    RouterLinkActive
  ],
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.scss']
})
export class HeaderComponent {

  @Input() user: any;
  @Output() toggleSidebar = new EventEmitter<void>();

  menu = MENU_CONFIG;
  moduloActivo = '';
  remainingSeconds = 0;
  dropdownOpen = false;

  private sessionInterval?: ReturnType<typeof setInterval>;

  constructor(
    private router: Router,
    private authService: AuthService,
    private cdr: ChangeDetectorRef
  ) {}

  ngOnInit(): void {
    this.detectarModuloActivo();

    this.router.events
      .pipe(filter(event => event instanceof NavigationEnd))
      .subscribe(() => {
        this.detectarModuloActivo();
      });

    this.updateSessionTime();

    this.sessionInterval = setInterval(() => {
      this.updateSessionTime();
    }, 1000);
  }

  ngOnDestroy(): void {
    if (this.sessionInterval) {
      clearInterval(this.sessionInterval);
    }
  }

  @HostListener('document:click')
  closeDropdown(): void {
    this.dropdownOpen = false;
  }

  detectarModuloActivo(): void {
    const segmento = this.router.url.split('/')[1];
    this.moduloActivo = segmento;
  }

  esActivo(key: string): boolean {
    return this.moduloActivo === key;
  }

  toggleMenu(): void {
    this.toggleSidebar.emit();
  }

  updateSessionTime(): void {
    this.remainingSeconds = this.authService.getRemainingSeconds();

    if (this.remainingSeconds <= 0) {
      this.authService.logout();
    }

    this.cdr.detectChanges();
  }

  formatTime(seconds: number): string {
    const minutes = Math.floor(seconds / 60);
    const secs = seconds % 60;

    return `${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
  }

  toggleDropdown(event: Event): void {
    event.stopPropagation();
    this.dropdownOpen = !this.dropdownOpen;
  }

  logout(event: Event): void {
    event.stopPropagation();
    this.authService.logout();
    this.router.navigate(['/login']);
  }

  irPerfil(event: Event): void {
    event.stopPropagation();
    this.router.navigate(['/perfil']);
    this.dropdownOpen = false;
  }
}