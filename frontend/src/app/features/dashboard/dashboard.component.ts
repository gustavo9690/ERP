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

  constructor(private router: Router) {}

  ngOnInit(): void {
    console.log('Ruta actual:', this.router.url);
  }



}