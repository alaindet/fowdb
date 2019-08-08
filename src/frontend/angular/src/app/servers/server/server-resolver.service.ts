import { ServersService } from './../servers.service';
import { Observable } from 'rxjs/Observable';
import {
  Resolve,
  RouterStateSnapshot,
  ActivatedRouteSnapshot
} from '@angular/router';
import { Injectable } from '@angular/core';

interface Server {
  id: number;
  name: string;
  status: string;
}

@Injectable({
  providedIn: 'root'
})
export class ServerResolverService implements Resolve<Server> {

  constructor(
    private serversService: ServersService
  ) {}

  resolve(
    route: ActivatedRouteSnapshot,
    state: RouterStateSnapshot
  ): Observable<Server> | Promise<Server> | Server {
    const id = +route.params['id'];
    return this.serversService.getServer(id);
  }

}
