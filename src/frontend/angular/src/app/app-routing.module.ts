import { ServerResolverService } from './servers/server/server-resolver.service';
import { CanDeactivateGuard } from './servers/edit-server/can-deactivate-guard.service';
import { AuthGuardService } from './auth-guard.service';
import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { HomeComponent } from './home/home.component';
// import { PageNotFoundComponent } from './page-not-found/page-not-found.component';
import { EditServerComponent } from './servers/edit-server/edit-server.component';
import { ServerComponent } from './servers/server/server.component';
import { ServersComponent } from './servers/servers.component';
import { UserComponent } from './users/user/user.component';
import { UsersComponent } from './users/users.component';
import { ErrorPageComponent } from './error-page/error-page.component';

const appRoutes: Routes = [

  // Users
  {
    path: 'users',
    component: UsersComponent,
    children: [
      { path: ':id', component: UserComponent }
    ]
  },

  // Servers
  {
    path: 'servers',
    // canActivate: [AuthGuardService],
    canActivateChild: [AuthGuardService],
    component: ServersComponent,
    children: [
      {
        path: ':id',
        component: ServerComponent,
        resolve: {
          server: ServerResolverService // Fetch data on init
        }
      },
      {
        path: ':id/edit',
        component: EditServerComponent,
        canDeactivate: [CanDeactivateGuard]
      }
    ]
  },

  // Default
  { path: '', component: HomeComponent, pathMatch: 'full' },
  // { path: '404', component: PageNotFoundComponent },
  {
    path: '404',
    component: ErrorPageComponent,
    data: { message: 'Page not found!' }
  },
  { path: '**', redirectTo: '404' }

];

@NgModule({
  imports: [
    // RouterModule.forRoot(appRoutes, { useHash: true })
    RouterModule.forRoot(appRoutes)
  ],
  exports: [
    RouterModule
  ]
})
export class AppRoutingModule {

}
