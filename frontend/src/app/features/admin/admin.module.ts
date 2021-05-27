import { CommonModule } from '@angular/common';
import { NgModule } from '@angular/core';

import { AdminFeatureRoutingModule } from './admin-routing.module';
import { AdminLayoutComponent } from './layout/admin.component';
import { HomeFeatureComponent } from './features/home/home.component';
import { CardsFeatureComponent } from './features/cards/cards.component';

@NgModule({
  imports: [
    CommonModule,
    AdminFeatureRoutingModule,
  ],
  declarations: [
    AdminLayoutComponent,
    HomeFeatureComponent,
    CardsFeatureComponent,
  ],
})
export class AdminFeatureModule {}
