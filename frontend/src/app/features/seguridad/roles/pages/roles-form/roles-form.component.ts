import { Component, OnInit } from '@angular/core';
import { RolesService } from '../../services/roles.service';
import { Roles } from '../../models/roles.model';
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule } from '@angular/forms';
import { MatCardModule } from '@angular/material/card';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatInputModule } from '@angular/material/input';
import { MatButtonModule } from '@angular/material/button';
import { MatIconModule } from '@angular/material/icon';
import { MatProgressSpinnerModule } from '@angular/material/progress-spinner';

@Component({
  selector: 'app-roles-form',
  standalone: true,
  imports: [
    CommonModule,
    ReactiveFormsModule,
    MatCardModule,
    MatFormFieldModule,
    MatInputModule,
    MatButtonModule,
    MatIconModule,
    MatProgressSpinnerModule
  ],
  templateUrl: './roles-form.component.html',
  styleUrls: ['./roles-form.component.scss']
})
export class RolesFormComponent implements OnInit {

  roles: Roles[] = [];

  constructor(private rolesService: RolesService) {}

  ngOnInit(): void {
    this.cargarRoles();
  }

  cargarRoles() {
    this.rolesService.obtenerRoles().subscribe(res => {
      this.roles = res.data.roles;
    });
  }

  eliminar(id: number) {
    this.rolesService.eliminarRol(id).subscribe(() => {
      this.cargarRoles();
    });
  }

}