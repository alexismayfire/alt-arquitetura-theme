async function restRequest(endpoint, callback) {
  const req = await fetch(`${restApi.url}${endpoint}`);
  return await req.json().then((res) => callback(res));
}

function isInViewport(elem) {
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

function animateParentClassList(elements, removeClasses, addClasses) {
  elements.forEach((elem) => {
    elem.parentElement.classList.add(...removeClasses);
    elem.parentElement.classList.remove(...addClasses);
  });
}

function getFeaturedImage(post) {
  const media = post['_embedded']['wp:featuredmedia'][0];
  return media['media_details']['sizes']['project-large']['source_url'];
}

function getCustomTaxonomy(post) {
  const term = post['_embedded']['wp:term'][0];
  return term[0]['name'];
}

function changeCurrentItem(proj) {
  const card = document.querySelector('.carousel-card');
  const cardContent = document.querySelector('.carousel-card-content');
  const titleTag = card.querySelector('.carousel-card-title');
  const segmentTag = card.querySelector('span');
  const imageTag = card.querySelector('.carousel-card-image');
  const linkTags = card.querySelectorAll('a');

  // const outClasses = ['fade-out', 'scale-out'];
  // const inClasses = ['fade-in', 'scale-in'];
  // animateParentClassList([cardContent, imageTag], inClasses, OutClasses);

  /*
  cardContent.parentElement.classList.remove('fade-in', 'scale-in');
  cardContent.parentElement.classList.add('fade-out', 'scale-out');
  imageTag.parentElement.classList.remove('fade-in', 'scale-in');
  imageTag.parentElement.classList.add('fade-out', 'scale-out');
  */

  setTimeout(function () {
    imageTag.setAttribute('src', proj.image);
    titleTag.innerHTML = proj.title;
    segmentTag.innerHTML = proj.segment;
    cardContent.parentElement.classList.add('fade-in', 'scale-in');
    cardContent.parentElement.classList.remove('fade-out', 'scale-out');
    imageTag.parentElement.classList.add('fade-in', 'scale-in');
    imageTag.parentElement.classList.remove('fade-out', 'scale-out');
  }, 300);

  linkTags.forEach((link) => link.setAttribute('href', proj.slug));
}

async function carouselItemListener(evt) {
  evt.preventDefault();

  const postId = evt.target.dataset.postId;
  const proj = window.projects[postId];

  changeCurrentItem(proj);

  const prev = window.projects[proj.prev];
  const next = window.projects[proj.next];
  const [buttonPrev, buttonNext] = document.querySelectorAll(
    '.carousel-item-content',
  );
  buttonPrev.dataset.postId = proj.prev;
  buttonPrev.classList.remove('fade-in', 'slide-in');
  buttonPrev.classList.add('fade-out', 'slide-out-left');
  setTimeout(function () {
    buttonPrev.querySelector('.carousel-item-title').innerHTML = prev.title;
    buttonPrev.querySelector('span').innerHTML = prev.segment;
    buttonPrev.classList.add('fade-in', 'slide-in');
    buttonPrev.classList.remove('fade-out', 'slide-out-left');
  }, 300);

  buttonNext.dataset.postId = proj.next;
  buttonNext.classList.remove('fade-in', 'slide-in');
  buttonNext.classList.add('fade-out', 'slide-out-right');
  setTimeout(function () {
    buttonNext.querySelector('.carousel-item-title').innerHTML = next.title;
    buttonNext.querySelector('span').innerHTML = next.segment;
    buttonNext.classList.add('fade-in', 'slide-in');
    buttonNext.classList.remove('fade-out', 'slide-out-right');
  }, 300);
}

async function carouselMobileItemListener(evt) {
  evt.preventDefault();

  const target = evt.target.parentElement;
  let postId = target.dataset.postId;
  if (!postId) {
    postId = target.parentElement.dataset.postId;
  }
  const proj = window.projects[postId];

  changeCurrentItem(proj);

  const buttonPrev = document.querySelector('.carousel-item-navleft');
  buttonPrev.dataset.postId = proj.prev;

  const buttonNext = document.querySelector('.carousel-item-navright');
  buttonNext.dataset.postId = proj.next;
}

function mapFeaturedProjectsIds(res) {
  return res.acf['projetos_itens']
    .reduce((acc, current) => (acc += `${current.ID},`), '')
    .slice(0, -1);
}

function mapFeaturedProjects(res) {
  // TODO: precisa reordenar com os dados da consulta original
  // A API retorna por ordem descendente de ID!
  const projects = res.reduce(
    (acc, item) => ({
      ...acc,
      [item.id]: {
        title: item.title.rendered,
        segment: getCustomTaxonomy(item),
        image: getFeaturedImage(item),
        slug: item.slug,
      },
    }),
    {},
  );

  window.projectsIds.forEach((key, index, arr) => {
    if (index === 0) {
      projects[key]['prev'] = arr[arr.length - 1];
      projects[key]['next'] = arr[index + 1];
    } else if (index === arr.length - 1) {
      projects[key]['prev'] = arr[index - 1];
      projects[key]['next'] = arr[0];
    } else {
      projects[key]['prev'] = arr[index - 1];
      projects[key]['next'] = arr[index + 1];
    }
  });

  return projects;
}

async function getFeaturedProjects() {
  // TODO: make a dynamic query for home page
  let endpoint = 'acf/v3/pages/9';
  const projectsIdsQuery = await restRequest(endpoint, mapFeaturedProjectsIds);

  window.projectsIds = projectsIdsQuery.split(',');

  endpoint = `wp/v2/project?_embed&include=${projectsIdsQuery}`;
  window.projects = await restRequest(endpoint, mapFeaturedProjects);
}

async function carouselInit() {
  await getFeaturedProjects();

  if (window.innerWidth > 768) {
    const selector = '.carousel-item-content[data-post-id]';
    const items = document.querySelectorAll(selector);
    items.forEach((item) =>
      item.addEventListener('click', carouselItemListener),
    );
  } else {
    // handleCarouselSwipe();
    const left = document.querySelector('.carousel-item-navleft');
    const right = document.querySelector('.carousel-item-navright');
    if (left && right) {
      left.addEventListener('click', carouselMobileItemListener);
      right.addEventListener('click', carouselMobileItemListener);
    }
  }
}

function handleCarouselSwipe() {
  const card = document.querySelector('.carousel-card');

  let swipedir, startX, startY, endX, endY;
  const thresholdX = window.innerWidth / 3;
  const thresholdY = thresholdX / 2;

  card.addEventListener('touchstart', function (evt) {
    evt.preventDefault();
    startX = evt.touches[0].clientX;
    startY = evt.touches[0].clientY;
  });

  card.addEventListener('touchend', function (evt) {
    evt.preventDefault();
    endX = evt.changedTouches[0].clientX;
    endY = evt.changedTouches[0].clientY;
    const distX = endX - startX;
    const distY = Math.abs(endY - startY);
    if (Math.abs(distX) >= thresholdX && distY <= thresholdY) {
      swipedir = distX > 0 ? 'right' : 'left';
    }
  });
}

function toggleMenu() {
  if (window.innerWidth > 768) {
    return;
  }

  document.querySelector('header.navbar').classList.toggle('is-open');
  const home = document.querySelector('#home');
  if (home) {
    home.querySelector('.hero-body').classList.toggle('is-invisible');
  }
  document.querySelector('main').classList.toggle('is-invisible');
}

function menuInit() {
  const navbar = document.querySelector('header.navbar');
  const menuHamb = document.querySelector('[data-target=navbarMenuHeroC]');

  menuHamb.addEventListener('click', toggleMenu);

  if (window.location.pathname === '/') {
    document.querySelectorAll('a[href^="/#"]').forEach((anchor) => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        toggleMenu();
        const href = this.getAttribute('href').split('/')[1];
        document.querySelector(href).scrollIntoView({
          behavior: 'smooth',
        });
      });
    });
  }
}

function singleProjectNavInit() {
  const galleryItems = document.querySelectorAll('.blocks-gallery-item');
  const contactSection = document.querySelector('#contato');
  let firstPhotoId, contactTitle, navs;

  if (galleryItems.length) {
    firstPhotoId = galleryItems[0].querySelector('img').dataset.id;
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
      if (isIntersecting) {
        if (target.classList.contains('blocks-gallery-item')) {
          navs.forEach((nav) => nav.classList.remove('is-hidden'));
        } else {
          navs.forEach((nav) => nav.classList.add('is-hidden'));
        }
      } else {
        if (
          target.classList.contains('blocks-gallery-item') &&
          target.querySelector('img').dataset.id === firstPhotoId &&
          intersectionRect.top > 0
        ) {
          navs.forEach((nav) => nav.classList.add('is-hidden'));
        }
      }
    });
  }
}

document.addEventListener('DOMContentLoaded', function () {
  carouselInit();
  menuInit();
  singleProjectNavInit();
});

function scrollInit(evt) {
  const elem = document.querySelector('.navbar');
  const elemHeight = elem.getBoundingClientRect().height;
  const home = window.location.pathname === '/';
  const container = document.querySelector('header ~ main');
  if (elem) {
    if (isInViewport(elem) && !window.location.hash) {
      if (!home) {
        container.style.marginTop = 0;
      }
      elem.classList.remove('nav-background', 'is-fixed-top');
    } else {
      if (!home) {
        container.style.marginTop = `${elemHeight}px`;
      }
      elem.classList.add('nav-background', 'is-fixed-top');
    }
  }
}

document.addEventListener('scroll', scrollInit);
