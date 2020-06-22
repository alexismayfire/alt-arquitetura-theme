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
    imgSrc: project['featured_image_src'],
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
  ].join('&_fields[]=');
  const projects = await restRequest(
    `wp/v2/project?per_page=100&_fields[]=${fields}`,
    getProjectsInfo,
  );
  window.allProjects = projects;
}

function infiniteScroll(projects) {
  window.observer = new IntersectionObserver(callback);
  const wrapper = document.querySelector('.projects');
  let size = document.querySelectorAll('.projects-card').length;
  let last = document.querySelector('.projects-card:last-child');
  console.log(last);
  window.observer.observe(last);

  function callback(entries) {
    entries.forEach((entry) => {
      if (entry.isIntersecting && last.id === entry.target.id) {
        const rows = size / 3;
        const lastRow = [];
        const projToAdd = projects.slice(size, size + 3);

        // Salva em lastRow os elementos para interpolar
        projToAdd.forEach((proj, index) => {
          const selector = `.projects-card:nth-child(${
            rows * (index + 1) + 1
          })`;
          lastRow.push(document.querySelector(selector));
        });

        // Adiciona os elementos ao DOM
        projToAdd.forEach((proj, index) => {
          const elem = createCard(proj);
          wrapper.insertBefore(createCard(proj), lastRow[index]);
        });

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
        window.observer.unobserve(last);
        if (size < projects.length) {
          last = document.querySelector('.projects-card:last-child');
          window.observer.observe(last);
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
    infiniteScroll(projects);
  }, 500);
}

function projectsFilter() {
  document
    .querySelectorAll('.button-filter:not(.is-active)')
    .forEach((elem) => elem.addEventListener('click', projectsFilterListener));
}

async function projectsInit() {
  await getProjects();
  infiniteScroll(window.allProjects);
  projectsFilter();
}

document.addEventListener('DOMContentLoaded', projectsInit);
