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

function getFeaturedImage(post) {
  const media = post['_embedded']['wp:featuredmedia'][0];
  return media['media_details']['sizes']['project-large']['source_url'];
}

function getCustomTaxonomy(post) {
  const term = post['_embedded']['wp:term'][0];
  return term[0]['name'];
}

async function carouselItemListener(evt) {
  evt.preventDefault();
  const postId = evt.target.dataset.postId;

  const card = document.querySelector('.carousel-card');
  const cardContent = document.querySelector('.carousel-card-content');
  const titleTag = card.querySelector('.carousel-card-title');
  const segmentTag = card.querySelector('span');
  const imageTag = card.querySelector('.carousel-card-image');
  const linkTags = card.querySelectorAll('a');

  const proj = window.projects[postId];
  cardContent.classList.add('fade-out');
  imageTag.parentElement.classList.add('fade-out');

  setTimeout(function () {
    imageTag.setAttribute('src', proj.image);
    cardContent.classList.add('fade-in');
    cardContent.classList.remove('fade-out');
    imageTag.parentElement.classList.add('fade-in');
    imageTag.parentElement.classList.remove('fade-out');
  }, 300);
  titleTag.innerHTML = proj.title;
  segmentTag.innerHTML = proj.segment;

  linkTags.forEach((link) => link.setAttribute('href', proj.slug));

  const prev = window.projects[proj.prev];
  const next = window.projects[proj.next];
  const [buttonPrev, buttonNext] = document.querySelectorAll(
    '.carousel-item-content',
  );
  buttonPrev.dataset.postId = proj.prev;
  buttonPrev.classList.add('fade-out');
  setTimeout(function () {
    buttonPrev.querySelector('.carousel-item-title').innerHTML = prev.title;
    buttonPrev.querySelector('span').innerHTML = prev.segment;
    buttonPrev.classList.add('fade-in');
    buttonPrev.classList.remove('fade-out');
  }, 300);

  buttonNext.dataset.postId = proj.next;
  buttonNext.classList.add('fade-out');
  setTimeout(function () {
    buttonNext.querySelector('.carousel-item-title').innerHTML = next.title;
    buttonNext.querySelector('span').innerHTML = next.segment;
    buttonNext.classList.add('fade-in');
    buttonNext.classList.remove('fade-out');
  }, 300);
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

  const selector = '.carousel-item-content[data-post-id]';
  const items = document.querySelectorAll(selector);
  items.forEach((item) => item.addEventListener('click', carouselItemListener));
}

function menuInit() {
  const menuHamb = document.querySelector('[data-target=navbarMenuHeroC]');
  menuHamb.addEventListener('click', function (evt) {
    document.querySelector('#navbarMenuHeroC').classList.toggle('is-active');
  });

  if (window.location.pathname === '/') {
    document.querySelectorAll('a[href^="/#"]').forEach((anchor) => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const href = this.getAttribute('href').split('/')[1];
        document.querySelector(href).scrollIntoView({
          behavior: 'smooth',
        });
      });
    });
  }
}

document.addEventListener('DOMContentLoaded', function () {
  carouselInit();
  menuInit();
});

function scrollInit(evt) {
  const elem = document.querySelector('.navbar');
  const elemHeight = elem.getBoundingClientRect().height;
  const home = window.location.pathname === '/';
  const hero = document.querySelector('.hero.is-fullheight');
  const container = document.querySelector('header ~ div');
  if (elem) {
    if (isInViewport(elem)) {
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
