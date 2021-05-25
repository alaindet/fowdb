import { Component } from '@angular/core';

@Component({
  selector: 'fow-navbar',
  templateUrl: './navbar.component.html',
  styleUrls: ['./navbar.component.scss'],
  host: {
    class: 'g-container-item --full-width',
  },
})
export class NavbarComponent {}
