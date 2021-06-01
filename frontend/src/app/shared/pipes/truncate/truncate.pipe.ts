import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
  name: 'fowTruncate',
  pure: true,
})
export class TruncatePipe implements PipeTransform {
  transform(value: string, length: number, ellipsis = '...'): any {
    return value.length > length
      ? value.slice(0, length) + ellipsis
      : value;
  }
}
