async function restRequest(endpoint, callback) {
  const req = await fetch(`${restApi.url}${endpoint}`);
  return await req.json().then((res) => callback(res));
}

function isDesktop() {
  return window.innerWidth > 968;
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

function animateCards(wrapper) {
  wrapper.querySelectorAll('.fade-out').forEach((card) => {
    card.classList.add('fade-in');
    setTimeout(function () {
      card.classList.remove('fade-out');
    }, 250);
  });
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

async function getColumnResizeOptions() {
  const cards = document.querySelectorAll('.projects-card');
  const sizes = { 0: 0, 1: 0, 2: 0 };
  const promises = [];

  cards.forEach((card, index) => {
    const img = card.querySelector('img');
    const promise = new Promise((resolve) => {
      if (img.complete) {
        // Se a imagem já está carregada, é seguro pegar a bounding box
        const height = card.getBoundingClientRect().height;
        sizes[index % 3] += Math.floor(height) + 2;
        resolve();
      } else {
        img.onload = function () {
          // Caso contrário, usa como handler do evento onload
          const height = card.getBoundingClientRect().height;
          sizes[index % 3] += Math.floor(height) + 2;
          resolve();
        };
      }
    });
    promises.push(promise);
  });

  // Aguarda o download das novas imagens de destaque associadas
  // Se for a exibição inicial, vai resolver todas as Promises instantaneamente
  await Promise.all(promises);

  const ordered = Object.values(sizes).sort((a, b) => a - b);
  // Como tem 3 colunas, o último valor é a coluna mais alta
  const containerHeight = ordered[2];
  // Usamos a coluna mais baixa pra associar o Observer
  const minHeight = ordered[0];
  // Retornamos valores de 1 a 3, invertidos: 3 = 1a coluna
  const observerColumn = Object.values(sizes)
    .reverse()
    .reduce((acc, curr, i) => (curr === minHeight ? i + 1 : acc), 0);

  return { containerHeight, observerColumn };
}

function getCardMaxHeight(projects) {
  const wrapper = document.querySelector('.projects');
  // Pegamos a largura para obter o ratio de redução de cada imagem
  const wrapperWidth = wrapper.getBoundingClientRect().width;
  // 1.5em padding + 2em margins
  const maxWidth = (wrapperWidth - 24 - 36) / 3;
  let maxHeight = 0;

  projects.forEach((proj) => {
    // A imagem vai ser reduzida proporcionalmente!
    const ratio = maxWidth / proj.imgWidth;
    // Calculamos a altura no DOM antes de inserir, com os dados da API
    // 20 = padding-bottom de cada card
    const height = Math.floor(proj.imgHeight * ratio + 20) + 1;
    if (height > maxHeight) {
      maxHeight = height;
    }
  });

  // Agora, sabemos o valor que devemos adicionar ao container de cards
  return maxHeight;
}

async function getNextObserver(cards) {
  if (isDesktop()) {
    const size = cards.length;
    const { containerHeight, observerColumn } = await getColumnResizeOptions();
    const wrapper = document.querySelector('.projects');
    // Aumentamos o container antes de inserir os elementos no DOM
    wrapper.style.height = `${containerHeight + 48}px`;
    // Retornamos o último elemento da coluna com menor altura
    lastCard = cards[size - observerColumn];
  } else {
    // No mobile, sempre vai ser a última
    lastCard = document.querySelector('.projects-card:last-child');
  }

  return lastCard;
}

async function loadMore(projects) {
  const wrapper = document.querySelector('.projects');
  cards = document.querySelectorAll('.projects-card');
  const projToAdd = projects.slice(cards.length, cards.length + 3);
  if (!projToAdd.length) {
    // Nenhum item para adicionar, só remove o Observer
    window.observer.unobserve(lastCard);
    return;
  }

  if (isDesktop()) {
    // Ajusta a altura do container antes de adicionar ao DOM
    // Aqui, será calculado com valores proporcionais!
    const maxHeight = getCardMaxHeight(projToAdd);
    const currentHeight = wrapper.getBoundingClientRect().height;
    // 24 = padding do container
    wrapper.style.height = `${currentHeight + maxHeight + 24}px`;
  }

  projToAdd.forEach((proj) => wrapper.appendChild(createCard(proj)));

  // Animação dos elementos entrando
  setTimeout(function () {
    animateCards(wrapper);
  }, 250);

  // Atualização do observer para o último elemento
  cards = document.querySelectorAll('.projects-card');
  window.observer.unobserve(lastCard);

  if (cards.length < projects.length) {
    lastCard = await getNextObserver(cards);
    window.observer.observe(lastCard);
  } else if (isDesktop()) {
    // Se não tem mais elementos pra carregar, faz um último ajuste fino
    const { containerHeight } = await getColumnResizeOptions();
    const wrapper = document.querySelector('.projects');
    wrapper.style.height = `${containerHeight + 24}px`;
  }
}

async function infiniteScrollProjects(projects) {
  const options = { threshold: 1 };
  window.observer = new IntersectionObserver(callback, options);

  let cards = document.querySelectorAll('.projects-card');
  let lastCard = await getNextObserver(cards);
  window.observer.observe(lastCard);

  function callback(entries) {
    entries.forEach((entry) => {
      if (entry.isIntersecting && lastCard.id === entry.target.id) {
        loadMore(projects);
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
  // Filtrando os projetos de acordo com o botão escolhido
  const projects = segment
    ? window.allProjects.filter((proj) => proj.segment === segment)
    : window.allProjects;

  // Removemos o handler pra evitar possíveis bugs
  window.observer.disconnect();

  const wrapper = document.querySelector('.projects');
  // Adiciona a animação antes da remoção
  wrapper
    .querySelectorAll('.projects-card')
    .forEach((card) => card.classList.add('fade-out'));

  setTimeout(function () {
    // A remoção fica dentro do setTimeout, para ter uma transição mais suave
    wrapper.querySelectorAll('.projects-card').forEach((card) => card.remove());

    if (isDesktop()) {
      // Aqui, vamos calcular, para os elementos de entrada, a altura da
      // primeira e segunda linha, de forma proporcional
      // Precisamos inicializar novamente o height do container:
      // diferentes categorias podem ter mais ou menos elementos!
      const row1 = getCardMaxHeight(projects.slice(0, 3));
      const row2 = getCardMaxHeight(projects.slice(3, 6));
      wrapper.style.height = `${row1 + row2 + 24}px`;
    }

    const initialProjects = projects.slice(0, 6);
    // Agora podemos adicionar os cards iniciais, sem quebrar o layout
    initialProjects.forEach((proj) => wrapper.appendChild(createCard(proj)));
    animateCards(wrapper);

    infiniteScrollProjects(projects);
  }, 250);
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
