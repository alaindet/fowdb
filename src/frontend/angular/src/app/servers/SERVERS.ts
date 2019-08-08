import { Server } from './models/server.model';

export const SERVERS: Server[] = [
  {
    id: 1,
    name: 'Productionserver',
    status: 'online'
  },
  {
    id: 2,
    name: 'Testserver',
    status: 'offline'
  },
  {
    id: 3,
    name: 'Devserver',
    status: 'offline'
  }
];
