export interface AuthUser {
  idUsuario: number;
  usuario: string;

  nombres?: string;
  apellidoPaterno?: string;
  apellidoMaterno?: string;

  estado: number;

  idEmpleado?: number | null;
}