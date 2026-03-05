import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { NavigationEnd, Router, RouterLink, RouterLinkActive } from '@angular/router';
import { MENU_CONFIG } from '../../../core/config/menu.config';
import { filter } from 'rxjs/internal/operators/filter';

@Component({
  selector: 'app-sidebar',
  standalone: true,
  imports: [
    CommonModule,
    RouterLink,
    RouterLinkActive
  ],
  templateUrl: './sidebar.component.html',
  styleUrls: ['./sidebar.component.scss']
})

export class SidebarComponent {

  menu = MENU_CONFIG;
  moduloActivo: any;
  openMenus: Set<string> = new Set();

  constructor(private router: Router) {}

  ngOnInit(): void {

    this.detectarModuloActivo();

    this.router.events
      .pipe(filter(event => event instanceof NavigationEnd))
      .subscribe(() => {
        this.detectarModuloActivo();
      });
  }

  detectarModuloActivo() {
    const segmento = this.router.url.split('/')[1];

    this.moduloActivo = this.menu.find(m => m.key === segmento);

    this.expandirSegunRuta();
  }

  expandirSegunRuta() {

    if (!this.moduloActivo) return;

    const currentUrl = this.router.url;

    this.moduloActivo.items.forEach((item: any) => {

      if (item.children) {

        const tieneActivo = item.children.some((sub: any) =>
          currentUrl.startsWith(sub.route)
        );

        if (tieneActivo) {
          this.openMenus.add(item.label);
        }
      }
    });
  }

  toggleMenu(label: string) {

    if (this.openMenus.has(label)) {
      this.openMenus.delete(label);
    } else {
      this.openMenus.add(label);
    }

  }

  isOpen(label: string): boolean {
    return this.openMenus.has(label);
  }

}