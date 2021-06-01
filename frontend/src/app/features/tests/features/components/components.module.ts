import { NgModule } from '@angular/core';

import { ButtonComponentModule } from 'src/app/shared/components';

const components = [
  ButtonComponentModule,
];

@NgModule({
  imports: [...components],
  exports: [...components],
})
export class TestsComponentModule {}
