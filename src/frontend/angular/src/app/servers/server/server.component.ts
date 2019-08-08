import { Subscription } from 'rxjs';
import { ActivatedRoute, Params, Router, Data } from '@angular/router';
import { Component, OnInit, OnDestroy } from '@angular/core';

import { ServersService } from '../servers.service';

@Component({
  selector: 'app-server',
  templateUrl: './server.component.html',
  styleUrls: ['./server.component.css']
})
export class ServerComponent implements OnInit, OnDestroy {

  server: {id: number, name: string, status: string};
  // paramsSubscription: Subscription;
  resolverSubscription: Subscription;

  constructor(
    private serversService: ServersService,
    private route: ActivatedRoute,
    private router: Router
  ) {}

  ngOnInit() {
    // this.fetchServer(this.route.snapshot.params['id']);
    // this.paramsSubscription = this.route.params.subscribe(
    //   (params: Params) => this.fetchServer(params['id'])
    // );
    this.resolverSubscription = this.route.data
      .subscribe(
        (data: Data) => {
          this.server = data['server'];
        }
      );
  }

  ngOnDestroy(): void {
    // this.paramsSubscription.unsubscribe();
    this.resolverSubscription.unsubscribe();
  }

  fetchServer(input: string): void {
    const id = +input;
    this.server = this.serversService.getServer(id);
  }

  onEditAbsolutePath(): void {
    this.router.navigate(['/servers', this.server.id, 'edit']);
  }

  onEditRelativePath() {
    this.router.navigate(['edit'], { relativeTo: this.route });
  }

}
