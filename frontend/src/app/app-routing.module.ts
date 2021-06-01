import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { environment } from 'src/environments/environment';

const HOME = '';

let routes: Routes = [
  {
    path: HOME,
    loadChildren: () => import('./features/public/public.module')
      .then(m => m.PublicFeatureModule),
  },
  {
    path: 'admin',
    loadChildren: () => import('./features/admin/admin.module')
      .then(m => m.AdminFeatureModule),
  },
  {
    path: '**',
    redirectTo: HOME,
  }
];

// Add tests on development only
if (!environment.production) {

  const tests = {
    path: 'tests',
    loadChildren: () => import('./features/tests/tests.module')
      .then(m => m.TestsFeatureModule),
  };

  routes = [...routes.slice(0, -1), tests, routes[routes.length - 1]];
}

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule {}
