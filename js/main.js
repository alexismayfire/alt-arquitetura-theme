import { qs } from './utils';

import scrollInit from './scroll';
import menuInit from './menu';
import carouselInit from './carousel';
import contactFormInit from './contactForm';
import relatedProjectsInit from './relatedProjects';
import projectsInit from './projects';
import postsInit from './blog';

document.addEventListener('DOMContentLoaded', function () {
  window.isMenuOpen = false;
  window.currentScrollY = 0;

  menuInit();

  if (qs('#home')) {
    carouselInit();
    contactFormInit();
  } else if (qs('#project')) {
    relatedProjectsInit();
    contactFormInit();
  } else if (qs('#projects')) {
    projectsInit();
  } else if (qs('#blog')) {
    postsInit();
  }
});

document.addEventListener('scroll', scrollInit);
