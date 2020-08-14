import { isMobile, qs, qsAll } from './utils';
import animate from './animations';

function createLinkedList() {
  const projects = window.featuredProjects.reduce((acc, current) => {
    return {
      ...acc,
      [current.id]: {
        ...current,
      },
    };
  }, {});

  window.featuredProjects.forEach((value, index, arr) => {
    if (index === 0) {
      projects[value.id]['prev'] = arr[arr.length - 1].id;
      projects[value.id]['next'] = arr[index + 1].id;
    } else if (index === arr.length - 1) {
      projects[value.id]['prev'] = arr[index - 1].id;
      projects[value.id]['next'] = arr[0].id;
    } else {
      projects[value.id]['prev'] = arr[index - 1].id;
      projects[value.id]['next'] = arr[index + 1].id;
    }
  });

  return projects;
}

function changeCurrentItem(proj) {
  const card = qs('.carousel-card');
  let [img, content] = card.children;

  animate.scale([content, img], 'out');

  img.setAttribute('href', proj.slug);
  content.setAttribute('href', proj.slug);

  setTimeout(function () {
    // TODO: change based on img.onload!
    img.firstElementChild.setAttribute('src', proj.image);
    const [title, segment] = content.firstElementChild.children;
    title.innerHTML = proj.title;
    segment.innerHTML = proj.segment;
    animate.scale([content, img], 'in');
  }, 300);
}

function changeButton(elem, proj, anim) {
  elem.dataset.postId = proj.id;
  anim(elem, 'out');

  setTimeout(function () {
    const [title, segment] = elem.children;
    title.innerHTML = proj.title;
    segment.innerHTML = proj.segment;
    anim(elem, 'in');
  }, 300);
}

async function carouselMobileItemListener(evt) {
  evt.preventDefault();

  const target = evt.target.parentElement;
  const { postId } = target.dataset
    ? target.dataset
    : target.parentElement.dataset;
  const proj = window.projects[postId];

  changeCurrentItem(proj);

  const selector = '.carousel-item-nav';
  qs(`${selector}left`).dataset.postId = proj.prev;
  qs(`${selector}right`).dataset.postId = proj.next;
}

async function carouselItemListener(evt) {
  evt.preventDefault();

  const { postId } = evt.target.dataset;
  const proj = window.projects[postId];

  changeCurrentItem(proj);

  const prev = window.projects[proj.prev];
  const next = window.projects[proj.next];
  const [btnPrev, btnNext] = qsAll('.carousel-item-content');

  changeButton(btnPrev, prev, animate.slideLeft);
  changeButton(btnNext, next, animate.slideRight);
}

async function carouselInit() {
  window.projects = createLinkedList();

  if (isMobile()) {
    // handleCarouselSwipe();
    const left = qs('.carousel-item-navleft');
    const right = qs('.carousel-item-navright');
    if (left && right) {
      left.addEventListener('click', carouselMobileItemListener);
      right.addEventListener('click', carouselMobileItemListener);
    }
  } else {
    const items = qsAll('.carousel-item-content[data-post-id]');
    items.forEach((item) =>
      item.addEventListener('click', carouselItemListener),
    );
  }
}

export default carouselInit;
