export function qs(selector) {
  return document.querySelector(selector);
}

export function qsAll(selector) {
  return document.querySelectorAll(selector);
}

export async function restRequest(endpoint, callback) {
  const req = await fetch(`${restApi.url}${endpoint}`);
  return await req.json().then((res) => callback(res));
}

export function isMobile() {
  return window.innerWidth < 768;
}

export function isInViewport(elem) {
  const bounding = elem.getBoundingClientRect();
  const hero = document.querySelector('.hero.is-fullheight');

  if (hero) {
    return hero.getBoundingClientRect().height > window.scrollY;
  }

  if (elem.classList.contains('is-fixed-top')) {
    return bounding.height + 16 > window.scrollY;
  }

  return bounding.top + bounding.height > 0;
}

function classListOperation(elem, op, css) {
  Array.isArray(css) ? elem.classList[op](...css) : elem.classList[op](css);
}

function changeElemClassList(elem, op, css) {
  Array.isArray(elem) || elem.length
    ? elem.forEach((e) => classListOperation(e, op, css))
    : classListOperation(elem, op, css);
}

/**
 * Add one or more CSS classes to an element (or list of elements)
 * @param {Element|NodeList} elem - element or elements that will receive new CSS classes
 * @param {string|string[]} classes CSS classes to add to the element(s). Existing classes will be ignored.
 */
export function addClasses(elem, classes) {
  changeElemClassList(elem, 'add', classes);
}

/**
 * Remove one or more CSS classes from an element (or list of elements)
 * @param {Element|NodeList} elem - element or elements that will receive new CSS classes
 * @param {string|string[]} classes CSS classes to add to the element(s). Existing classes will be ignored.
 */
export function removeClasses(elem, classes) {
  changeElemClassList(elem, 'remove', classes);
}
