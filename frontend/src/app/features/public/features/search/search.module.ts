import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';

import { SearchFeatureComponent } from './search.component';

@NgModule({
  imports: [
    CommonModule,
    RouterModule.forChild([
      { path: '', component: SearchFeatureComponent }
    ]),
  ],
  declarations: [
    SearchFeatureComponent,
  ],
})
export class PublicSearchFeatureModule {}
