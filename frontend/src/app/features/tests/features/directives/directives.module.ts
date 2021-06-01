import { NgModule } from '@angular/core';

import { BackDirectiveModule } from 'src/app/shared/directives';

const directives = [
  BackDirectiveModule,
];

@NgModule({
  imports: [...directives],
  exports: [...directives],
})
export class TestsDirectivesModule {}
