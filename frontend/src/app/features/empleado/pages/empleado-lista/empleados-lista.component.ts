import { CommonModule } from "@angular/common";
import { Component } from "@angular/core";

@Component({
    selector: 'app-empleado-lista',
    standalone: true,
    imports: [CommonModule],
    templateUrl: './empleados-lista.component.html',
    styleUrls: ['./empleados-lista.component.scss']
})
export class EmpleadoListaComponent {}