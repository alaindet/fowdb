import { CommonModule } from '@angular/common';
import { NgModule } from '@angular/core';

import { BackDirectiveModule } from 'src/app/shared/directives';
import { TestsFeatureRoutingModule } from './tests-routing.module';
import { TestsLayoutComponent } from './layout/tests.component';
import { TestsWelcomeComponent } from './components/welcome/welcome.component';
import { TestsComponentModule } from './features/components/components.module';
import { TestsDirectivesModule } from './features/directives/directives.module';
import { TestsPipesModule } from './features/pipes/pipes.module';
import { TestButtonComponent } from './features/components/button/button.component';
import { TestTruncateComponent } from './features/pipes/truncate/truncate.component';
import { TestBackComponent } from './features/directives/back/back.component';

@NgModule({
  imports: [
    CommonModule,
    TestsFeatureRoutingModule,
    TestsComponentModule,
    TestsDirectivesModule,
    TestsPipesModule,
    BackDirectiveModule,
  ],
  declarations: [
    TestsLayoutComponent,
    TestsWelcomeComponent,
    TestButtonComponent,
    TestBackComponent,
    TestTruncateComponent,
  ],
})
export class TestsFeatureModule {}
