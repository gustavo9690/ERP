import { ChangeDetectorRef, Component, signal } from '@angular/core';
import { Router, RouterOutlet } from '@angular/router';
import { RolesService } from './roles.service';
import { Roles } from './roles.model';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './app.html',
  styleUrl: './app.scss'
})
export class App {
  roles: Roles[] = [];
  loading = false;
  error?: string;

  constructor(private rolesService: RolesService, private router: Router, private cdr: ChangeDetectorRef) {
    
     this.rolesService.obtenerRoles().subscribe({
      next: (res) => {
        this.roles = res.data.roles;
      },
      error: (err) => {
        console.error('Error:', err);
      },
      complete: () => {
        console.log('Suscripción finalizada');
        this.cdr.detectChanges();
      }
    });
    
  }

  
    
    

  cargarRoles() {
    this.loading = true;
    this.error = undefined;

    this.rolesService.obtenerRoles().subscribe({
      next: (res) => {
        this.roles = res.data.roles;
      },
      error: (err) => {
        console.error('Error:', err);
      },
      complete: () => {
        console.log('Suscripción finalizada');
      }
    });
  }

  eliminar(id: number) {
    this.rolesService.eliminarRol(id).subscribe(() => {
      this.cargarRoles();
    });
  }

}
