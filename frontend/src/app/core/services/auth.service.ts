import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { BehaviorSubject, tap } from 'rxjs';
import { environment } from '../../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  private apiUrl = environment.apiUrl;

  // 👇 Usuario reactivo
  private userSubject = new BehaviorSubject<any>(this.getUserFromToken());
  user$ = this.userSubject.asObservable();

  constructor(private http: HttpClient) {}

  login(usuario: string, clave: string) {
    return this.http.post<any>(`${this.apiUrl}/auth/auth/login`, {
      usuario,
      clave
    }).pipe(
      tap(response => {
        if (response.success === true || response.status === 'success') {
          const token = response.data?.token || response.token;
          if (token) {
            localStorage.setItem('token', token);
            const payload = JSON.parse(atob(token.split('.')[1]));
            this.userSubject.next(payload);
          }
        } else if (response.success === false || response.status === 'error') {
          const errorMessage = response.error || response.message || 'Credenciales incorrectas';
          throw new Error(errorMessage);
        } else {
          throw new Error('Respuesta inesperada del servidor');
        }
      })
    );
  }

  logout() {
    localStorage.removeItem('token');
    this.userSubject.next(null); // 👈 limpia usuario
  }

  getToken(): string | null {
    return localStorage.getItem('token');
  }

  isAuthenticated(): boolean {
    const token = this.getToken();
    if (!token) return false;

    try {
      const payload = JSON.parse(atob(token.split('.')[1]));
      const now = Math.floor(Date.now() / 1000);
      return payload.exp > now;
    } catch {
      return false;
    }
  }

  getUserFromToken(): any {
    const token = this.getToken();
    if (!token) return null;

    try {
      const payload = JSON.parse(atob(token.split('.')[1]));
      return payload.data;
    } catch {
      return null;
    }
  }
}