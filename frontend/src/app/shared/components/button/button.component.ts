import { Component, HostBinding, Input } from '@angular/core';

export interface ButtonComponentInput {
  color: (
    | 'primary'
    | 'secondary'
  );
  size: (
    | 'small'
    | 'medium'
    | 'large'
  );
}

@Component({
  selector: '[fow-button]',
  template: `<ng-content></ng-content>`,
  styleUrls: ['./button.component.scss'],
})
export class ButtonComponent {

  @HostBinding('class')
  cssClasses: string = '';

  @Input() color: ButtonComponentInput['color'] = 'secondary';
  @Input() size: ButtonComponentInput['size'] = 'medium';

  ngOnInit(): void {
    this.cssClasses = [
      `size-${this.size}`,
      `color-${this.color}`,
    ].join(' ');
  }
}
