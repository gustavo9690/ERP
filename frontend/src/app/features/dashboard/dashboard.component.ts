import { CommonModule } from "@angular/common";
import { Component } from "@angular/core";
import { Roles } from "../seguridad/roles/models/roles.model";
import { RolesService } from "../seguridad/roles/services/roles.service";
import { MatCardModule } from "@angular/material/card";
import { MatInputModule } from "@angular/material/input";
import { MatFormFieldModule } from "@angular/material/form-field";
import { MatButtonModule } from "@angular/material/button";
import { MatIconModule } from "@angular/material/icon";
import { MatProgressSpinnerModule } from "@angular/material/progress-spinner";
import { Router } from "@angular/router";

@Component({
  selector: 'app-dashboard',
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
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.scss']
})
export class DashboardComponent {


  roles: Roles[] = [];
  loading = false;
  error?: string;

  constructor(private rolesService: RolesService,private router: Router) {}

  ngOnInit(): void {
    console.log('Ruta actual:', this.router.url);
    this.cargarRoles();
  }

  cargarRoles() {
    this.loading = true;
    this.error = undefined;

    this.rolesService.obtenerRoles().subscribe({
      next: res => {
        this.roles = res.data.roles;
        this.loading = false;
      },
      error: err => {
        console.error('Error cargando roles:', err);
        this.error = 'No se pudo cargar la lista de roles. Intenta de nuevo.';
        this.loading = false;
      }
    }); 
  }

  eliminar(id: number) {
    this.rolesService.eliminarRol(id).subscribe(() => {
      this.cargarRoles();
    });
  }



}