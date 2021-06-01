import { NgModule } from '@angular/core';

import { TruncatePipeModule } from 'src/app/shared/pipes';

const pipes = [
  TruncatePipeModule,
];

@NgModule({
  imports: [...pipes],
  exports: [...pipes],
})
export class TestsPipesModule {}
