import { Server } from './models/server.model';
import { SERVERS } from './SERVERS';

export class ServersService {

  private servers = SERVERS;

  addServer(server: Server): void {
    this.servers.push(server);
  }

  getServers(): Server[] {
    return this.servers;
  }

  getServer(id: number): Server {
    const server = this.servers.find(
      (s) => {
        return s.id === id;
      }
    );
    return server;
  }

  updateServer(id: number, serverInfo: {name: string, status: string}): void {
    const server = this.servers.find(
      (s) => {
        return s.id === id;
      }
    );
    if (server) {
      server.name = serverInfo.name;
      server.status = serverInfo.status;
    }
  }

  deleteServer(id: number): void {
    this.servers = this.servers.filter(server => server.id !== id);
  }

}
