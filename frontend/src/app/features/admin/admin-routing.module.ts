import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';

import { AdminLayoutComponent } from './layout/admin.component';
import { HomeFeatureComponent } from './features/home/home.component';
import { CardsFeatureComponent } from './features/cards/cards.component';

const routes: Routes = [
  {
    path: '',
    component: AdminLayoutComponent,
    children: [
      {
        path: '',
        pathMatch: 'full',
        component: HomeFeatureComponent,
      },
      {
        path: 'cards',
        component: CardsFeatureComponent,
      },
    ],
  }
];
@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class AdminFeatureRoutingModule {}
