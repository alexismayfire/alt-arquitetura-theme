import { isMobile, qs, qsAll } from './utils';

function toggleMenu() {
  if (!isMobile()) {
    return;
  }

  qs('header.navbar').classList.toggle('is-open');
  window.isMenuOpen = !window.isMenuOpen;

  // This will prevent scrolling when the menu is open
  if (window.isMenuOpen) {
    window.currentScrollY = window.scrollY;
    document.body.style.position = 'fixed';
    qs('.button-whatsapp').classList.add('is-hidden');
  } else {
    document.body.style.position = '';
    window.scrollTo(0, window.currentScrollY || 0);
    window.currentScrollY = 0;
    qs('.button-whatsapp').classList.remove('is-hidden');
  }
}

function menuSectionListener(evt) {
  evt.preventDefault();
  toggleMenu();

  // Instead of reloading the page, just scroll to the section
  const href = evt.target.getAttribute('href').split('/')[1];
  qs(href).scrollIntoView({ behavior: 'smooth' });
}

function menuInit() {
  qs('[data-target=navbarMenuHeroC]').addEventListener('click', toggleMenu);

  if (qs('#home')) {
    // In the home page, we add scroll behaviour to the sections
    const sections = qsAll('a[href^="/#"]');
    sections.forEach((a) => a.addEventListener('click', menuSectionListener));
  } else if (qs('#project')) {
    // In the project detail page, only the contact section exists
    const contactSection = qs('a[href^="/#contato"]');
    contactSection.addEventListener('click', menuSectionListener);
  }
}

export default menuInit;
