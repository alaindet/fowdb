import { Component } from '@angular/core';

@Component({
  templateUrl: './tests.component.html',
  styleUrls: ['./tests.component.scss'],
})
export class TestsLayoutComponent {

  isSidebarOpen = true;

  onSidebarToggle(open: null | boolean = null): void {
    this.isSidebarOpen = (open !== null) ? open : !this.isSidebarOpen;
  }
}
