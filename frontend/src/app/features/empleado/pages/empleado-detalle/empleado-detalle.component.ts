import { CommonModule } from "@angular/common";
import { Component } from "@angular/core";

@Component({
  selector: 'app-empleado-detalle',
  standalone: true,
  imports: [
    CommonModule
  ],
  templateUrl: './empleado-detalle.component.html',
  styleUrls: ['./empleado-detalle.component.scss']
})
export class EmpleadoDetalleComponent {
    empresas = 12;
    usuarios = 48;
    ventas = 25300;
    modulos = 8;
}