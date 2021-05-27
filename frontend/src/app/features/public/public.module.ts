import { CommonModule } from '@angular/common';
import { NgModule } from '@angular/core';

import { PublicFeatureRoutingModule } from './public-routing.module';
import { PublicLayoutComponent } from './layout/public.component';
import { HomeFeatureComponent } from './features/home/home.component';

@NgModule({
  imports: [
    CommonModule,
    PublicFeatureRoutingModule,
  ],
  declarations: [
    PublicLayoutComponent,
    HomeFeatureComponent,
  ],
})
export class PublicFeatureModule {}
