import { USERS } from './../USERS';
import { User } from '../models/user.model';
import { ActivatedRoute, Params } from '@angular/router';
import { Component, OnInit, OnDestroy } from '@angular/core';
import { Subscription } from 'rxjs';

@Component({
  selector: 'app-user',
  templateUrl: './user.component.html',
  styleUrls: ['./user.component.css']
})
export class UserComponent implements OnInit, OnDestroy {

  user: User;
  id: number;
  paramsSubscription: Subscription;

  constructor(
    private route: ActivatedRoute
  ) {}

  ngOnInit(): void {
    this.fetchUser(this.route.snapshot.params['id']);
    this.paramsSubscription = this.route.params.subscribe(
      (params: Params) => this.fetchUser(params['id'])
    );
  }

  ngOnDestroy(): void {
    this.paramsSubscription.unsubscribe();
  }

  fetchUser(input: string): void {
    const id = +input;
    this.id = id;
    this.user = USERS.find(user => user.id === id);
  }

}
