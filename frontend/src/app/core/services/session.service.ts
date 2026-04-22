import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { BehaviorSubject } from 'rxjs';
import { AuthService } from './auth.service';

@Injectable({
  providedIn: 'root'
})
export class SessionService {

  private inactivityTimeout: ReturnType<typeof setTimeout> | null = null;
  private responseTimeout: ReturnType<typeof setTimeout> | null = null;
  private countdownInterval: ReturnType<typeof setInterval> | null = null;

  private listenersRegistered = false;
  private monitoringStarted = false;
  private popupVisible = false;

  private popupSubject = new BehaviorSubject<boolean>(false);
  popupState$ = this.popupSubject.asObservable();

  private countdownSubject = new BehaviorSubject<number>(120);
  countdown$ = this.countdownSubject.asObservable();

  constructor(
    private authService: AuthService,
    private router: Router
  ) {}

  startMonitoring(): void {
    if (!this.authService.isAuthenticated()) {
      return;
    }

    if (this.monitoringStarted) {
      return;
    }

    this.monitoringStarted = true;

    if (!this.listenersRegistered) {
      this.registerActivityListeners();
      this.listenersRegistered = true;
    }

    this.resetInactivityTimer();
  }

  stopMonitoring(): void {
    if (this.inactivityTimeout) {
      clearTimeout(this.inactivityTimeout);
      this.inactivityTimeout = null;
    }

    if (this.responseTimeout) {
      clearTimeout(this.responseTimeout);
      this.responseTimeout = null;
    }

    if (this.countdownInterval) {
      clearInterval(this.countdownInterval);
      this.countdownInterval = null;
    }

    this.popupVisible = false;
    this.monitoringStarted = false;

    this.popupSubject.next(false);
    this.countdownSubject.next(120);
  }

  private registerActivityListeners(): void {
    const events = ['click', 'mousemove', 'keydown', 'scroll'];

    events.forEach(event => {
      window.addEventListener(event, () => {
        this.onUserActivity();
      });
    });
  }

  private onUserActivity(): void {
    if (!this.authService.isAuthenticated()) {
      return;
    }

    if (this.popupVisible) {
      return;
    }

    this.resetInactivityTimer();
  }

  private resetInactivityTimer(): void {
    if (this.inactivityTimeout) {
      clearTimeout(this.inactivityTimeout);
    }

    this.inactivityTimeout = setTimeout(() => {
      this.showPopup();
    }, 5*60*1000);
  }

  private showPopup(): void {
    if (this.popupVisible) {
      return;
    }

    this.popupVisible = true;
    this.popupSubject.next(true);
    this.countdownSubject.next(120);

    this.startCountdown();

    this.responseTimeout = setTimeout(() => {
      this.forceLogout();
    }, 2*60*1000);
  }

  private startCountdown(): void {
    if (this.countdownInterval) {
      clearInterval(this.countdownInterval);
    }

    this.countdownInterval = setInterval(() => {
      const current = this.countdownSubject.value;

      if (current > 0) {
        this.countdownSubject.next(current - 1);
      } else {
        if (this.countdownInterval) {
          clearInterval(this.countdownInterval);
          this.countdownInterval = null;
        }
      }
    }, 1000);
  }

  continueSession(): void {
    if (this.responseTimeout) {
      clearTimeout(this.responseTimeout);
      this.responseTimeout = null;
    }

    if (this.countdownInterval) {
      clearInterval(this.countdownInterval);
      this.countdownInterval = null;
    }

    this.popupVisible = false;
    this.popupSubject.next(false);
    this.countdownSubject.next(120);

    this.authService.refreshToken().subscribe({
      next: () => {
        this.resetInactivityTimer();
      },
      error: () => {
        this.forceLogout();
      }
    });
  }

  logoutNow(): void {
    this.forceLogout();
  }

  private forceLogout(): void {
    this.stopMonitoring();
    this.authService.logout();
    this.router.navigate(['/login']);
  }
}