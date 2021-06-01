import { Directive, HostListener } from '@angular/core';
import { Location } from '@angular/common';

@Directive({
  selector: '[fowBack]',
})
export class BackDirective {

  constructor(
    private location: Location,
  ) {}

  @HostListener('click')
  private onClick(): void {
    this.location.back();
  }
}
