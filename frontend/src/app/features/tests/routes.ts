import { COMMON_ROUTES } from './features/common/routes';
import { COMPONENTS_ROUTES } from './features/components/routes';
import { DIRECTIVES_ROUTES } from './features/directives/routes';
import { PIPES_ROUTES } from './features/pipes/routes';

export const TESTS_ROUTES = [
  {
    path: 'common',
    data: { label: 'Common' },
    children: [...COMMON_ROUTES],
  },
  {
    path: 'components',
    data: { label: 'Components' },
    children: [...COMPONENTS_ROUTES],
  },
  {
    path: 'directives',
    data: { label: 'Directives' },
    children: [...DIRECTIVES_ROUTES],
  },
  {
    path: 'pipes',
    data: { label: 'Pipes' },
    children: [...PIPES_ROUTES],
  },
];
