import { CommonModule } from '@angular/common';
import { NgModule } from '@angular/core';

import { BackDirectiveModule } from 'src/app/shared/directives';
import { TestsFeatureRoutingModule } from './tests-routing.module';
import { TestsLayoutComponent } from './layout/tests.component';
import { TestsWelcomeComponent } from './components/welcome/welcome.component';
import { TestsDirectivesModule } from './features/directives/directives.module';
import { TestsPipesModule } from './features/pipes/pipes.module';
import { TestsComponentModule } from './features/components/components.module';

// Test pages
import { TestColorsComponent } from './features/common/colors/colors.component';
import { TestLinksComponent } from './features/common/links/links.component';
import { TestTypographyComponent } from './features/common/typography/typography.component';
import { TestButtonComponent } from './features/components/button/button.component';;
import { TestBackComponent } from './features/directives/back/back.component';
import { TestTruncateComponent } from './features/pipes/truncate/truncate.component';

const testPages = [
  TestButtonComponent,
  TestBackComponent,
  TestTruncateComponent,
  TestColorsComponent,
  TestTypographyComponent,
  TestLinksComponent,
];

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
    ...testPages,
  ],
})
export class TestsFeatureModule {}
