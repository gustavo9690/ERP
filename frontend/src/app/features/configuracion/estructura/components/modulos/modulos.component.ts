import { CommonModule } from '@angular/common';
import { ChangeDetectorRef, Component, OnInit } from '@angular/core';
import { finalize } from 'rxjs';

import {
  DataTableAction,
  DataTableColumn,
  DataTableComponent
} from '../../../../../shared/components/UI/data-table/data-table.component';

import { ModuloService } from '../../services/modulo.service';
import { Modulo } from '../../models/modulo.model';

@Component({
  selector: 'app-modulos',
  standalone: true,
  imports: [
    CommonModule,
    DataTableComponent
  ],
  templateUrl: './modulos.component.html',
  styleUrls: ['./modulos.component.scss']
})
export class ModulosComponent implements OnInit {

  columns: DataTableColumn[] = [
    {
      key: 'idModulo',
      label: 'ID',
      type: 'number',
      width: '80px'
    },
    {
      key: 'nombreModulo',
      label: 'Módulo'
    },
    {
      key: 'codigoModulo',
      label: 'Código'
    },
    {
      key: 'fechaCreacion',
      label: 'Fecha de creación'
    },
    {
      key: 'fechaModificacion',
      label: 'Fecha de modificación'
    },
    {
      key: 'estadoModulo',
      label: 'Estado',
      type: 'status',
      width: '140px'
    }
  ];

  actions: DataTableAction[] = [
    {
      key: 'edit',
      label: 'Editar',
      icon: '✏️',
      type: 'primary'
    },
    {
      key: 'delete',
      label: 'Eliminar',
      icon: '🗑️',
      type: 'danger',
      visible: (row: Modulo) => row.estadoModulo === 1
    },
    {
      key: 'activate',
      label: 'Activar',
      icon: '↻',
      type: 'success',
      visible: (row: Modulo) => row.estadoModulo === 0
    }
  ];

  modulos: Modulo[] = [];

  loading: boolean = false;

  constructor(
    private moduloService: ModuloService,
    private cdr: ChangeDetectorRef
  ) {}

  ngOnInit(): void {
    this.cargarModulos();
  }

  cargarModulos(): void {
    this.loading = true;

    this.moduloService.listarModulos()
      .pipe(
        finalize(() => {
          this.loading = false;
          this.cdr.detectChanges();
        })
      )
      .subscribe({
        next: (modulos) => {
          this.modulos = modulos;
          this.cdr.detectChanges();
        }, 
        error: (error) => {
          console.error(error.message);
          this.cdr.detectChanges();
        }
      });
      this.cdr.detectChanges();
  }

  onTableAction(event: { action: DataTableAction; row: Modulo }): void {
    switch (event.action.key) {
      case 'edit':
        this.editarModulo(event.row);
        break;

      case 'delete':
        this.eliminarModulo(event.row);
        break;

      case 'activate':
        this.activarModulo(event.row);
        break;
    }
  }

  nuevoModulo(): void {
    console.log('Nuevo módulo');
  }

  editarModulo(modulo: Modulo): void {
    console.log('Editar módulo', modulo);
  }

  eliminarModulo(modulo: Modulo): void {
    console.log('Eliminar módulo', modulo);
  }

  activarModulo(modulo: Modulo): void {
    console.log('Activar módulo', modulo);
  }

  exportarModulos(): void {
    console.log('Exportar módulos');
  }

}