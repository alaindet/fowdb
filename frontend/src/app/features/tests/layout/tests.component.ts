import { Component } from '@angular/core';

import { TESTS_ROUTES } from '../routes';

@Component({
  templateUrl: './tests.component.html',
  styleUrls: ['./tests.component.scss'],
})
export class TestsLayoutComponent {

  isSidebarOpen = true;
  TESTS_ROUTES = TESTS_ROUTES;

  onSidebarToggle(open: null | boolean = null): void {
    this.isSidebarOpen = (open !== null) ? open : !this.isSidebarOpen;
  }
}
