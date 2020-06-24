async function restRequest(endpoint, callback) {
  const req = await fetch(`${restApi.url}${endpoint}`);
  return await req.json().then((res) => callback(res));
}

function getProjectsInfo(res) {
  return res.map((project) => ({
    id: project['id'],
    title: project['title']['rendered'],
    segment: project['segments'][0],
    permalink: project['link'],
    imgSrc: project['featured_image_src'][0],
    imgWidth: project['featured_image_src'][1],
    imgHeight: project['featured_image_src'][2],
  }));
}

function createCard({ id, title, permalink, imgSrc }) {
  const elem = document.createElement('div');
  elem.classList.add('projects-card', 'fade-out');
  elem.dataset.id = id;

  const anchor = document.createElement('a');
  anchor.setAttribute('href', permalink);
  const figure = document.createElement('figure');

  const img = document.createElement('img');
  img.setAttribute('src', imgSrc);

  const span = document.createElement('span');
  span.innerHTML = title;

  figure.appendChild(img);
  anchor.appendChild(figure);
  anchor.append(span);
  elem.appendChild(anchor);

  return elem;
}

async function getProjects() {
  const fields = [
    'id',
    'title',
    'link',
    'segments',
    'featured_media',
    'featured_image_src',
    'type',
  ].join('&_fields[]=');
  const projects = await restRequest(
    `wp/v2/project?per_page=100&_fields[]=${fields}`,
    getProjectsInfo,
  );
  window.allProjects = projects;
}

function getTallestCardHeight() {
  const cards = document.querySelectorAll('.projects-card');
  const sizes = { 0: 0, 1: 0, 2: 0 };

  cards.forEach((card, index) => {
    const height = card.getBoundingClientRect().height;
    sizes[index % 3] += Math.floor(height) + 1;
  });

  const ordered = Object.values(sizes).sort((a, b) => a - b);
  return ordered.pop();
}

function infiniteScrollProjects(projects) {
  const options = { threshold: 0.5 };
  window.observer = new IntersectionObserver(callback, options);

  const wrapper = document.querySelector('.projects');
  if (window.innerWidth > 968) {
    wrapper.style.height = `${getTallestCardHeight() + 24}px`;
  }
  const wrapperWidth = wrapper.getBoundingClientRect().width;
  const maxWidth = (wrapperWidth - 24 - 36) / 3; // 1.5em padding + 2em margins

  let size = document.querySelectorAll('.projects-card').length;
  let lastCard = document.querySelector('.projects-card:last-child');
  window.observer.observe(lastCard);

  function callback(entries) {
    entries.forEach((entry) => {
      if (entry.isIntersecting && lastCard.id === entry.target.id) {
        const projToAdd = projects.slice(size, size + 3);

        // Ajusta a altura do container, apenas adiciona se for mobile
        if (window.innerWidth > 968) {
          let maxHeight = 0;
          const cards = [];
          projToAdd.forEach((proj) => {
            const card = createCard(proj);
            const ratio = maxWidth / proj.imgWidth;
            const height = Math.floor(proj.imgHeight * ratio + 20) + 1;
            if (height > maxHeight) {
              maxHeight = height;
            }
            cards.push(createCard(proj));
          });
          const currentHeight = wrapper.getBoundingClientRect().height;
          wrapper.style.height = `${currentHeight + maxHeight}px`;
          cards.forEach((card) => wrapper.appendChild(card));
        } else {
          projToAdd.forEach((proj) => wrapper.append(createCard(proj)));
        }

        // Anima a entrada de cada elemento
        setTimeout(function () {
          document
            .querySelectorAll('.projects-card.fade-out')
            .forEach((elem) => {
              elem.classList.add('fade-in');
              elem.classList.remove('fade-out');
            });
        }, 500);

        // Atualização do observer para o último elemento
        size = document.querySelectorAll('.projects-card').length;
        window.observer.unobserve(lastCard);
        if (size < projects.length) {
          lastCard = document.querySelector('.projects-card:last-child');
          window.observer.observe(lastCard);
        } else {
          wrapper.style.height = `${getTallestCardHeight() + 24}px`;
        }
      }
    });
  }
}

function projectsFilterListener(evt) {
  const current = document.querySelector('.button-filter.is-active');
  current.addEventListener('click', projectsFilterListener);
  current.classList.remove('is-active');

  evt.target.removeEventListener('click', projectsFilterListener);
  evt.target.classList.add('is-active');

  const segment = parseInt(evt.target.dataset.segment);
  const projects = segment
    ? window.allProjects.filter((proj) => proj.segment === segment)
    : window.allProjects;

  const wrapper = document.querySelector('.projects');

  wrapper
    .querySelectorAll('.projects-card')
    .forEach((card) => card.classList.add('fade-out'));

  setTimeout(function () {
    window.observer.disconnect();
    wrapper.querySelectorAll('.projects-card').forEach((card) => card.remove());
    projects
      .slice(0, 6)
      .forEach((proj) => wrapper.appendChild(createCard(proj)));
    wrapper.querySelectorAll('.projects-card').forEach((card) => {
      card.classList.add('fade-in');
      card.classList.remove('fade-out');
    });
    infiniteScrollProjects(projects);
  }, 500);
}

function projectsFilter() {
  document
    .querySelectorAll('.button-filter:not(.is-active)')
    .forEach((elem) => elem.addEventListener('click', projectsFilterListener));
}

async function projectsInit() {
  await getProjects();
  infiniteScrollProjects(window.allProjects);
  projectsFilter();
}

document.addEventListener('DOMContentLoaded', projectsInit);
