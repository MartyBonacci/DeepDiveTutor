import {RouterModule, Routes} from "@angular/router";
import {HomeComponent} from "./components/home.component";
import {ProfileComponent} from "./components/profile.component";
import {BrowseComponent} from "./components/browse.component";
import {NavbarComponent} from "./components/navbar.component";
import {SessionService} from "./services/session.service";
import {FooterComponent} from "./components/footer.component";
import {ReviewComponent} from "./components/review.component";
import {SignInComponent} from "./components/sign-in.component";
import {SignInService} from "./services/sign-in.service";
import {CookieService} from "ng2-cookies";
import {SignUpComponent} from "./components/sign-up.component";
import {SignUpService} from "./services/sign-up.service";
import {APP_BASE_HREF} from "@angular/common";
import {ProfileService} from "./services/profile.service";
import {ProfileEditComponent} from "./components/profile-edit.component";
import {ImageComponent} from "./components/image.component";
import {RTProfileComponent} from "./components/rtprofile.component";


export const allAppComponents = [
	HomeComponent,
	ImageComponent,
	ProfileComponent,
	ProfileEditComponent,
	BrowseComponent,
	NavbarComponent,
	FooterComponent,
	RTProfileComponent,
	ReviewComponent,
	SignInComponent,
	SignUpComponent,
];

export const routes: Routes = [
	{path: "browse", component: BrowseComponent},
	{path: "profile", component: ProfileComponent},
	{path: "profile/:id" , component: RTProfileComponent},
	{path: "", component: HomeComponent}
];

export const appRoutingProviders: any[] = [{provide: APP_BASE_HREF, useValue: window["_base_href"]},CookieService, SessionService, SignInService, SignUpService, ProfileService];

export const routing = RouterModule.forRoot(routes);