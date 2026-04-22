import { CommonModule } from '@angular/common';
import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';

interface Modulo {
  idModulo: number;
  nombre: string;
  codigo: string;
  icono?: string | null;
  orden: number;
  estado: number;
}

interface Submodulo {
  idSubmodulo: number;
  idModulo: number;
  nombreModulo: string;
  nombre: string;
  codigo: string;
  icono?: string | null;
  orden: number;
  estado: number;
}

interface Opcion {
  idOpcion: number;
  idModulo: number;
  idSubmodulo: number;
  nombreModulo: string;
  nombreSubmodulo: string;
  nombre: string;
  codigo: string;
  ruta?: string | null;
  icono?: string | null;
  orden: number;
  visibleMenu: number;
  estado: number;
}

@Component({
  selector: 'app-estructura-page',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './estructura-page.component.html',
  styleUrls: ['./estructura-page.component.scss']
})
export class EstructuraPageComponent {

  activeTab: 'modulos' | 'submodulos' | 'opciones' = 'modulos';

  filtroSubmoduloModulo = '';
  filtroOpcionModulo = '';
  filtroOpcionSubmodulo = '';

  modulos: Modulo[] = [
    { idModulo: 1, nombre: 'Seguridad', codigo: 'SEGURIDAD', icono: 'shield', orden: 1, estado: 1 },
    { idModulo: 2, nombre: 'RRHH', codigo: 'RRHH', icono: 'users', orden: 2, estado: 1 },
    { idModulo: 3, nombre: 'Logística', codigo: 'LOGISTICA', icono: 'box', orden: 3, estado: 0 }
  ];

  submodulos: Submodulo[] = [
    { idSubmodulo: 1, idModulo: 1, nombreModulo: 'Seguridad', nombre: 'Usuarios', codigo: 'USUARIOS', icono: 'person', orden: 1, estado: 1 },
    { idSubmodulo: 2, idModulo: 1, nombreModulo: 'Seguridad', nombre: 'Roles', codigo: 'ROLES', icono: 'group', orden: 2, estado: 1 },
    { idSubmodulo: 3, idModulo: 2, nombreModulo: 'RRHH', nombre: 'Empleados', codigo: 'EMPLEADOS', icono: 'badge', orden: 1, estado: 1 },
    { idSubmodulo: 4, idModulo: 3, nombreModulo: 'Logística', nombre: 'Productos', codigo: 'PRODUCTOS', icono: 'inventory', orden: 1, estado: 0 }
  ];

  opciones: Opcion[] = [
    {
      idOpcion: 1,
      idModulo: 1,
      idSubmodulo: 1,
      nombreModulo: 'Seguridad',
      nombreSubmodulo: 'Usuarios',
      nombre: 'Lista usuarios',
      codigo: 'LISTA_USUARIOS',
      ruta: '/seguridad/usuarios/lista',
      icono: 'list',
      orden: 1,
      visibleMenu: 1,
      estado: 1
    },
    {
      idOpcion: 2,
      idModulo: 1,
      idSubmodulo: 1,
      nombreModulo: 'Seguridad',
      nombreSubmodulo: 'Usuarios',
      nombre: 'Nuevo usuario',
      codigo: 'NUEVO_USUARIO',
      ruta: '/seguridad/usuarios/nuevo',
      icono: 'add',
      orden: 2,
      visibleMenu: 1,
      estado: 1
    },
    {
      idOpcion: 3,
      idModulo: 1,
      idSubmodulo: 2,
      nombreModulo: 'Seguridad',
      nombreSubmodulo: 'Roles',
      nombre: 'Lista roles',
      codigo: 'LISTA_ROLES',
      ruta: '/seguridad/roles/lista',
      icono: 'list',
      orden: 1,
      visibleMenu: 1,
      estado: 1
    },
    {
      idOpcion: 4,
      idModulo: 2,
      idSubmodulo: 3,
      nombreModulo: 'RRHH',
      nombreSubmodulo: 'Empleados',
      nombre: 'Lista empleados',
      codigo: 'LISTA_EMPLEADOS',
      ruta: '/rrhh/empleado/lista',
      icono: 'list',
      orden: 1,
      visibleMenu: 1,
      estado: 0
    }
  ];

  changeTab(tab: 'modulos' | 'submodulos' | 'opciones'): void {
    this.activeTab = tab;
  }

  get submodulosFiltrados(): Submodulo[] {
    if (!this.filtroSubmoduloModulo) {
      return this.submodulos;
    }

    return this.submodulos.filter(
      item => item.idModulo === Number(this.filtroSubmoduloModulo)
    );
  }

  get opcionesFiltradas(): Opcion[] {
    return this.opciones.filter(item => {
      const coincideModulo = this.filtroOpcionModulo
        ? item.idModulo === Number(this.filtroOpcionModulo)
        : true;

      const coincideSubmodulo = this.filtroOpcionSubmodulo
        ? item.idSubmodulo === Number(this.filtroOpcionSubmodulo)
        : true;

      return coincideModulo && coincideSubmodulo;
    });
  }

  get submodulosDisponiblesParaFiltro(): Submodulo[] {
    if (!this.filtroOpcionModulo) {
      return this.submodulos;
    }

    return this.submodulos.filter(
      item => item.idModulo === Number(this.filtroOpcionModulo)
    );
  }

  onChangeModuloOpcion(): void {
    this.filtroOpcionSubmodulo = '';
  }

  getEstadoLabel(estado: number): string {
    return estado === 1 ? 'Activo' : 'Inactivo';
  }
}