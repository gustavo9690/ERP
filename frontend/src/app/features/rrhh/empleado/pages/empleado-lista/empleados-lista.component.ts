import { CommonModule } from "@angular/common";
import { Component } from "@angular/core";
import { CardComponent } from "../../../../../shared/components/UI/card/card.component";
import { PageHeaderComponent } from "../../../../../shared/components/UI/page-header/page-header.component";
@Component({
    selector: 'app-empleado-lista',
    standalone: true,
    imports: [CommonModule, CardComponent, PageHeaderComponent],
    templateUrl: './empleados-lista.component.html',
    styleUrls: ['./empleados-lista.component.scss']
})
export class EmpleadoListaComponent {}