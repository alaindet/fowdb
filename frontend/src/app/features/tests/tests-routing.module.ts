import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { TestsLayoutComponent } from './layout/tests.component';
import { TESTS_ROUTES } from './routes';
import { TestsWelcomeComponent } from './components/welcome/welcome.component';

const routes: Routes = [
  {
    path: '',
    component: TestsLayoutComponent,
    children: [
      {
        path: '',
        component: TestsWelcomeComponent,
        data: { label: 'Home' },
      },
      ...TESTS_ROUTES,
    ],
  },
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class TestsFeatureRoutingModule {}
