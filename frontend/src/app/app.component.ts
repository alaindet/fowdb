import { Component } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Component({
  selector: 'fow-root',
  templateUrl: './app.component.html',
})
export class AppComponent {
  title = 'frontend';

  constructor(
    private http: HttpClient,
  ) {}

  ngOnInit(): void {
    const url = 'http://localhost:8080/users/login';
    const email = 'john.doe@example.com';
    const password = 'johndoe';
    const credentials = { email, password };
    this.http.post(url, credentials).subscribe(response => {
      console.log('Try to login', response);
    });
  }
}
