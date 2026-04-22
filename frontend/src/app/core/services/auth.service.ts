import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { BehaviorSubject, tap } from 'rxjs';
import { environment } from '../../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  private apiUrl = environment.apiUrl + '/seguridad';

  private userSubject = new BehaviorSubject<any>(this.getStoredUser());
  user$ = this.userSubject.asObservable();

  constructor(private http: HttpClient) {}

  login(usuario: string, clave: string) {
    return this.http.post<any>(`${this.apiUrl}/auth/auth/login`, {
      usuario,
      clave
    }).pipe(
      tap(response => {
        if (response.success === true || response.status === 'success') {
          const token = response.data?.token;
          const refreshToken = response.data?.refreshToken;
          const user = response.data;

          if (token) {
            localStorage.setItem('token', token);
          }

          if (refreshToken) {
            localStorage.setItem('refreshToken', refreshToken);
          }

          if (user) {
            localStorage.setItem('user', JSON.stringify(user));
            this.userSubject.next(user);
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

  refreshToken() {
    const refreshToken = localStorage.getItem('refreshToken');

    return this.http.post<any>(`${this.apiUrl}/auth/auth/refresh`, {
      refreshToken
    }).pipe(
      tap(response => {
        if (response.success === true || response.status === 'success') {
          const newToken = response.data?.token;
          const newRefreshToken = response.data?.refreshToken;

          if (newToken) {
            localStorage.setItem('token', newToken);
          }

          if (newRefreshToken) {
            localStorage.setItem('refreshToken', newRefreshToken);
          }
        }
      })
    );
  }

  logout() {
    localStorage.removeItem('token');
    localStorage.removeItem('refreshToken');
    localStorage.removeItem('user');
    this.userSubject.next(null);
  }

  getToken(): string | null {
    return localStorage.getItem('token');
  }

  getRefreshToken(): string | null {
    return localStorage.getItem('refreshToken');
  }

  getStoredUser(): any {
    const user = localStorage.getItem('user');
    if (!user) return null;

    try {
      return JSON.parse(user);
    } catch {
      return null;
    }
  }

  isAuthenticated(): boolean {
    const token = this.getToken();
    if (!token) return false;

    try {
      const payload = JSON.parse(atob(token.split('.')[1]));
      const exp = payload.exp;

      if (!exp) return false;

      return Date.now() < exp * 1000;
    } catch {
      return false;
    }
  }

  getUserFromToken(): any {
    const token = this.getToken();
    if (!token) return null;

    try {
      return JSON.parse(atob(token.split('.')[1]));
    } catch {
      return null;
    }
  }

  getTokenPayload(): any | null {
    const token = this.getToken();
    if (!token) return null;

    try {
      return JSON.parse(atob(token.split('.')[1]));
    } catch {
      return null;
    }
  }

  getTokenExpiration(): number | null {
    const payload = this.getTokenPayload();
    return payload?.exp ?? null;
  }

  getRemainingSeconds(): number {
    const exp = this.getTokenExpiration();
    if (!exp) return 0;

    const now = Math.floor(Date.now() / 1000);
    return Math.max(exp - now, 0);
  }
}