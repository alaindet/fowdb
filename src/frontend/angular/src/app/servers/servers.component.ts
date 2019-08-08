import { Component, OnInit } from '@angular/core';
import { ServersService } from './servers.service';
import { Router, ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-servers',
  templateUrl: './servers.component.html',
  styleUrls: ['./servers.component.css']
})
export class ServersComponent implements OnInit {
  public servers: {id: number, name: string, status: string}[] = [];

  constructor(
    private serversService: ServersService,
    private router: Router,
    private route: ActivatedRoute
  ) {}

  ngOnInit() {
    this.servers = this.serversService.getServers();
  }

  onProgrammaticReload(): void {
    // This does not work as it redirects to /servers/servers
    // this.router.navigate(['servers'], {
    //   relativeTo: this.route
    // });
    this.router.navigate(['/servers']);
  }

  onProgrammaticChange(id: number): void {
    this.router.navigate(
      ['/servers', id, 'edit'],
      {
        queryParams: { allowEdit: 1 },
        fragment: 'loading'
      }
    );
  }

}
