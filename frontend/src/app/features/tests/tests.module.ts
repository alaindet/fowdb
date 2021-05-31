import { CommonModule } from '@angular/common';
import { NgModule } from '@angular/core';

import { TestsFeatureRoutingModule } from './tests-routing.module';
import { TestsLayoutComponent } from './layout/tests.component';
import { TestsWelcomeComponent } from './components/welcome/welcome.component';

@NgModule({
  imports: [
    CommonModule,
    TestsFeatureRoutingModule,
  ],
  declarations: [
    TestsLayoutComponent,
    TestsWelcomeComponent,
  ],
})
export class TestsFeatureModule {}
