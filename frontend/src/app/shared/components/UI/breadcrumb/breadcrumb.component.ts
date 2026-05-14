import { Component, Input } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ActivatedRoute, NavigationEnd, Router, RouterModule } from '@angular/router';
import { filter } from 'rxjs/internal/operators/filter';

@Component({
  selector: 'app-breadcrumb',
  standalone: true,
  imports: [CommonModule, RouterModule],
  templateUrl: './breadcrumb.component.html',
  styleUrls: ['./breadcrumb.component.scss']
})
export class BreadcrumbComponent {

  breadcrumbs: any[] = [];

  constructor(
    private router: Router,
    private route: ActivatedRoute
  ) {

    this.router.events
      .pipe(filter(event => event instanceof NavigationEnd))
      .subscribe(() => {
        this.breadcrumbs = this.buildBreadcrumb(this.route.root);
      });

  }

  buildBreadcrumb(route: ActivatedRoute, url: string = '', breadcrumbs: any[] = []): any[] {

    const children = route.children;

    if (children.length === 0) {
      return breadcrumbs;
    }

    for (const child of children) {

      const routeURL = child.snapshot.url.map(segment => segment.path).join('/');

      if (routeURL !== '') {
        url += `/${routeURL}`;
      }

      const label = child.snapshot.data['breadcrumb'];

      if (label) {
        breadcrumbs.push({ label, url });
      }

      return this.buildBreadcrumb(child, url, breadcrumbs);
    }

    return breadcrumbs;
  }

}