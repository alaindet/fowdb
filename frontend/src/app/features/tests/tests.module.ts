import { CommonModule } from '@angular/common';
import { NgModule } from '@angular/core';

import { TestsFeatureRoutingModule } from './tests-routing.module';
import { TestsLayoutComponent } from './layout/tests.component';
import { TestsWelcomeComponent } from './components/welcome/welcome.component';
import { TestsComponentModule } from './features/components/components.module';
import { TestsDirectivesModule } from './features/directives/directives.module';
import { TestsPipesModule } from './features/pipes/pipes.module';

@NgModule({
  imports: [
    CommonModule,
    TestsFeatureRoutingModule,
    TestsComponentModule,
  ],
  declarations: [
    TestsLayoutComponent,
    TestsWelcomeComponent,
  ],
})
export class TestsFeatureModule {}
