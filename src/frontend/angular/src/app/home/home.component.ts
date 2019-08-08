import { AuthService } from './../auth.service';
import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.css']
})
export class HomeComponent implements OnInit {

  constructor(
    private router: Router,
    private authService: AuthService
  ) {}

  ngOnInit() {
  }

  onClick(feature: string): void {
    this.router.navigate([`/${feature}`]);
  }

  onLogin(): void {
    // console.log('HomeComponent.onLogin()');
    this.authService.login();
  }

  onLogout(): void {
    // console.log('HomeComponent.onLogout()');
    this.authService.logout();
  }

}
