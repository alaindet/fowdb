import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { PublicLayoutComponent } from './layout/public.component';
import { HomeFeatureComponent } from './features/home/home.component';

const routes: Routes = [
  {
    path: '',
    component: PublicLayoutComponent,
    children: [
      {
        path: '',
        component: HomeFeatureComponent,
      },
      {
        path: 'search',
        loadChildren: () => import('./features/search/search.module')
          .then(m => m.PublicSearchFeatureModule),
      },
    ],
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class PublicFeatureRoutingModule {}
