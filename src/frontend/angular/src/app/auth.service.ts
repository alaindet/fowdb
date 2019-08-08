import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  loggedIn = false;

  login() {
    console.log('Logging in...');
    this.loggedIn = true;
  }

  logout() {
    console.log('Logging out...');
    this.loggedIn = false;
  }

  isAuthenticated(): Promise<boolean> {
    return new Promise<boolean>(
      (resolve, reject) => {

        // Simulate time delay
        // setTimeout(() => { resolve(this.loggedIn); }, 400);
        resolve(this.loggedIn);

      }
    );
  }

}
