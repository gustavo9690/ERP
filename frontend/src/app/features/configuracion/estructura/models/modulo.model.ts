export interface Modulo {
  idModulo: number;
  nombreModulo: string;
  codigoModulo: string;
  icono?: string | null;
  orden: number;
  estadoModulo: number;
  fechaCreacion?: string | null;
  fechaModificacion?: string | null;
}
