import { CommonModule } from '@angular/common';
import { Component, Input } from '@angular/core';
import { NavigationEnd, Router, RouterLink, RouterLinkActive } from '@angular/router';
import { MENU_CONFIG } from '../../../core/config/menu.config';
import { filter } from 'rxjs/internal/operators/filter';

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
  menu = MENU_CONFIG;
  moduloActivo: string = '';

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
    this.moduloActivo = segmento;
  }

  esActivo(key: string): boolean {
    return this.moduloActivo === key;
  }

}