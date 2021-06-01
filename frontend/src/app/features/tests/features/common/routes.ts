import { TestColorsComponent } from './colors/colors.component';
import { TestLinksComponent } from './links/links.component';
import { TestTypographyComponent } from './typography/typography.component';

export const COMMON_ROUTES = [
  {
    path: 'colors',
    component: TestColorsComponent,
    data: { label: 'Colors' },
  },
  {
    path: 'links',
    component: TestLinksComponent,
    data: { label: 'Links' },
  },
  {
    path: 'typography',
    component: TestTypographyComponent,
    data: { label: 'Typography' },
  },
];
