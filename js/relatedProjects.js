import { qs, qsAll, addClasses, removeClasses } from './utils';

function relatedProjectsInit() {
  const galleryItems = qsAll('.wp-block-image');
  const contactSection = qs('#contato');
  let firstPhotoSrc, contactTitle, navs;

  if (galleryItems.length) {
    firstPhotoSrc = galleryItems[0].querySelector('img').getAttribute('src');
    contactTitle = contactSection.querySelector('h2');
    navs = document.querySelectorAll('.project-nav');

    const options = { threshold: 0.25 };
    window.observer = new IntersectionObserver(callback, options);
    galleryItems.forEach((item) => window.observer.observe(item));
    window.observer.observe(contactTitle);
  }

  function callback(entries) {
    entries.forEach((entry) => {
      const { isIntersecting, intersectionRect, target } = entry;
      const isImage = target.classList.contains('wp-block-image');
      const firstImg =
        isImage &&
        target.querySelector('img').getAttribute('src') === firstPhotoSrc;

      if (isIntersecting) {
        isImage
          ? removeClasses(navs, 'is-hidden')
          : addClasses(navs, 'is-hidden');
      } else if (intersectionRect.top > 0) {
        !firstImg
          ? removeClasses(navs, 'is-hidden')
          : addClasses(navs, 'is-hidden');
      }
    });
  }
}

export default relatedProjectsInit;
