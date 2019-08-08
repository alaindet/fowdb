import { ActivatedRoute, Params, Router } from '@angular/router';
import { Component, OnInit, OnDestroy } from '@angular/core';
import { ServersService } from '../servers.service';
import { Subscription, Observable } from 'rxjs';

@Component({
  selector: 'app-edit-server',
  templateUrl: './edit-server.component.html',
  styleUrls: ['./edit-server.component.css']
})
export class EditServerComponent implements OnInit, OnDestroy {
  server: {id: number, name: string, status: string};
  serverName = '';
  serverStatus = '';
  allowed = false;
  queryParamsSubscription: Subscription;
  changesSaved = false;

  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private serversService: ServersService
  ) { }

  ngOnInit() {
    // console.log(this.route.snapshot.queryParams);
    // console.log(this.route.snapshot.fragment);

    this.queryParamsSubscription = this.route.queryParams.subscribe(
      (queryParams: Params) => {
        this.allowed = queryParams['allowEdit'] === '1' ? true : false;
      }
    );

    this.fetchServer(this.route.snapshot.params['id']);

    this.route.params.subscribe(
      (routeParams: Params) => {
        this.fetchServer(routeParams['id']);
      }
    );

  }

  private fetchServer(input: string): void {
    const id = +input;
    this.server = this.serversService.getServer(id);
    this.updateForm();
  }

  private updateForm(): void {
    this.serverName = this.server.name;
    this.serverStatus = this.server.status;
  }

  ngOnDestroy(): void {
    this.queryParamsSubscription.unsubscribe();
  }

  onUpdateServer() {
    const id = this.server.id;
    const server = { name: this.serverName, status: this.serverStatus };
    this.serversService.updateServer(id, server);
    this.changesSaved = true;
    this.router.navigate(['../'], {relativeTo: this.route});
  }

  canDeactivate(): Observable<boolean> | Promise<boolean> | boolean {

    // You've allowed to edit and already edited: ask upon leaving
    // confirm() shows an alert and returns a boolean
    const formWasTouched = (
      this.serverName !== this.server.name ||
      this.serverStatus !== this.server.status
    );
    if (this.allowed && formWasTouched && !this.changesSaved) {
      return confirm('Do you want to discard changes for this server?');
    }

    // You're not allowed and/or did not edit anything: you may leave
    return true;

  }

}
