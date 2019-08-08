import { Subscription } from 'rxjs';
import { ActivatedRoute, Data } from '@angular/router';
import { Component, OnInit, OnDestroy } from '@angular/core';

@Component({
  selector: 'app-error-page',
  templateUrl: './error-page.component.html',
  styleUrls: ['./error-page.component.css']
})
export class ErrorPageComponent implements OnInit, OnDestroy {

  message: string;
  url: string;
  dataSubscription: Subscription;

  constructor(
    private route: ActivatedRoute
  ) {}

  ngOnInit(): void {
    this.dataSubscription = this.route.data.subscribe(
      (data: Data) => {
        this.message = data['message'];
      }
    );
  }

  ngOnDestroy(): void {
    this.dataSubscription.unsubscribe();
  }

}
