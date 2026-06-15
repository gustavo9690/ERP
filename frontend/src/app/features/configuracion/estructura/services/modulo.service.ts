import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { map } from 'rxjs/operators';

import { environment } from '../../../../../environments/environment';

import { ApiResponse } from '../../../../core/models/api-response.model';
import { Modulo } from '../models/modulo.model';

@Injectable({
  providedIn: 'root'
})
export class ModuloService {

  private apiUrl = `${environment.apiUrl}/configuracion`;

  constructor(private http: HttpClient) {}

  listarModulos(): Observable<Modulo[]> {

    return this.http
      .post<ApiResponse<Modulo[]>>(
        `${this.apiUrl}/estructura/estructura/listar_modulos`,
        {}
      )
      .pipe(

        map(response => {

          if (response.status === 'success') {
            return response.data;
          }

          throw new Error(
            response.message || 'Error al listar módulos'
          );

        })

      );

  }

}