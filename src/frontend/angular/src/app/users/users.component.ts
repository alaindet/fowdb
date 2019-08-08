import { Component } from '@angular/core';
import { USERS } from './USERS';

@Component({
  selector: 'app-users',
  templateUrl: './users.component.html',
  styleUrls: ['./users.component.css']
})
export class UsersComponent {
  users = USERS;
}
