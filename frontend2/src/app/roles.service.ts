import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Roles } from './roles.model';
import { Observable } from 'rxjs';
import { environment } from '../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class RolesService {

  private api = environment.apiUrl + '/seguridad/roles';

  constructor(private http: HttpClient) {}

  obtenerRoles(): Observable<any> {
    return this.http.get(`${this.api}/obtener`);
  }

  crearRol(data: Roles): Observable<any> {
    return this.http.post(`${this.api}/crear`, data);
  }

  actualizarRol(data: Roles): Observable<any> {
    return this.http.put(`${this.api}/actualizar`, data);
  }

  eliminarRol(id: number): Observable<any> {
    return this.http.delete(`${this.api}/eliminar/${id}`);
  }
}