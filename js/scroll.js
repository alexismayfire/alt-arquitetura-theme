import { isInViewport, qs, addClasses, removeClasses } from './utils';

function scrollInit(evt) {
  const elem = qs('.navbar');
  const elemHeight = elem.getBoundingClientRect().height;
  const home = qs('#home');
  const container = qs('header ~ main');

  if (elem) {
    const css = ['nav-background', 'is-fixed-top'];
    if (isInViewport(elem) && !window.location.hash) {
      if (!home) {
        container.style.marginTop = 0;
      }
      removeClasses(elem, css);
    } else {
      if (!home) {
        container.style.marginTop = `${elemHeight}px`;
      }
      addClasses(elem, css);
    }
  }
}

export default scrollInit;
