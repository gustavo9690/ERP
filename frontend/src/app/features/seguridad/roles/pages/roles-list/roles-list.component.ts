import { ChangeDetectorRef, Component, OnInit } from '@angular/core';
import { RolesService } from '../../services/roles.service';
import { Roles } from '../../models/roles.model';
import { CommonModule } from '@angular/common';
import { Router } from '@angular/router';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatCardModule } from '@angular/material/card';
import { MatInputModule } from '@angular/material/input';
import { MatButtonModule } from '@angular/material/button';
import { MatIconModule } from '@angular/material/icon';
import { MatProgressSpinnerModule } from '@angular/material/progress-spinner';

@Component({
  selector: 'app-roles-list',
  standalone: true,
  imports: [
    CommonModule,
    MatCardModule,
    MatFormFieldModule,
    MatInputModule,
    MatButtonModule,
    MatIconModule,
    MatProgressSpinnerModule
  ],
  templateUrl: './roles-list.component.html',
  styleUrls: ['./roles-list.component.scss']
})
export class RolesListComponent implements OnInit {

  roles: Roles[] = [];
  loading = false;
  error?: string;

  constructor(private rolesService: RolesService, private router: Router, private cdr: ChangeDetectorRef) {}

  ngOnInit(): void {
    this.cargarRoles();
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
        console.log('Roles obtenidos:', this.roles);
        this.loading = false;
        this.cdr.detectChanges();
      }
    });
  }

  eliminar(id: number) {
    this.rolesService.eliminarRol(id).subscribe(() => {
      this.cargarRoles();
    });
  }

}