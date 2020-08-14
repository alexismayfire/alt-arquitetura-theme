import animate from './animations';
import { isMobile, qs, qsAll } from './utils';

function getWrapper() {
  return qs('.projects');
}

function getCards() {
  return qsAll('.projects-card');
}

function getAddedCards(wrapper) {
  return wrapper.querySelectorAll('.fade-out');
}

function createCard({ id, title, permalink, imgSrc }) {
  const elem = document.createElement('div');
  elem.classList.add('projects-card', 'fade-out');
  elem.dataset.id = id;

  const html = `
    <a href="${permalink}">
      <figure>
        <img src="${imgSrc}" alt=${title} />
      </figure>
      <span>${title}</span>
    </a>
  `;
  elem.innerHTML = html.trim();

  return elem;
}

async function getColumnResizeOptions() {
  const cards = getCards();
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
  const wrapper = getWrapper();
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

async function getNextObserver() {
  const cards = getCards();

  if (!isMobile()) {
    const { containerHeight, observerColumn } = await getColumnResizeOptions();
    // Aumentamos o container antes de inserir os elementos no DOM
    const wrapper = getWrapper();
    wrapper.style.height = `${containerHeight + 48}px`;
    // Retornamos o último elemento da coluna com menor altura
    return cards[cards.length - observerColumn];
  }

  // No mobile, sempre vai ser a última
  return cards[cards.length - 1];
}

async function loadMore(projects, lastCard) {
  const wrapper = getWrapper();
  let cards = getCards();

  const projToAdd = projects.slice(cards.length, cards.length + 3);
  if (!projToAdd.length) {
    // Nenhum item para adicionar, só remove o Observer
    window.observer.unobserve(lastCard);
    return;
  }

  if (!isMobile()) {
    // Ajusta a altura do container antes de adicionar ao DOM
    // Aqui, será calculado com valores proporcionais!
    const maxHeight = getCardMaxHeight(projToAdd);
    const currentHeight = wrapper.getBoundingClientRect().height;
    // 24 = padding do container
    wrapper.style.height = `${currentHeight + maxHeight + 24}px`;
  }

  projToAdd.forEach((proj) => wrapper.appendChild(createCard(proj)));
  const addedCards = getAddedCards(wrapper);

  // Animação dos elementos entrando
  setTimeout(function () {
    animate.scale(addedCards, 'in');
  }, 250);

  // Atualização do observer para o último elemento
  window.observer.unobserve(lastCard);

  if (cards.length + projToAdd.length < projects.length) {
    const newlastCard = await getNextObserver();
    window.observer.observe(newlastCard);
  } else if (!isMobile()) {
    // Se não tem mais elementos pra carregar, faz um último ajuste fino
    const { containerHeight } = await getColumnResizeOptions();
    wrapper.style.height = `${containerHeight + 24}px`;
  }
}

async function infiniteScrollProjects(projects) {
  const lastCard = await getNextObserver();

  window.observer = new IntersectionObserver(callback, { threshold: 1 });
  window.observer.observe(lastCard);

  function callback(entries) {
    entries.forEach((entry) => {
      if (entry.isIntersecting && lastCard.id === entry.target.id) {
        loadMore(projects, lastCard);
      }
    });
  }
}

function projectsFilterBySegment(segment) {
  // Filtrando os projetos de acordo com o botão escolhido
  const projects = segment
    ? window.projects.filter((proj) => proj.segment.includes(segment))
    : window.projects;

  // Removemos o handler pra evitar possíveis bugs
  window.observer.disconnect();

  const wrapper = getWrapper();
  let cards = getCards();
  // Adiciona a animação antes da remoção
  cards.forEach((card) => card.classList.add('fade-out'));

  setTimeout(function () {
    // A remoção fica dentro do setTimeout, para ter uma transição mais suave
    cards.forEach((card) => card.remove());

    if (!isMobile()) {
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

    cards = getCards();
    setTimeout(function () {
      animate.scale(cards, 'in');
    }, 250);
    infiniteScrollProjects(projects);
  }, 250);
}

function projectsButtonFilterListener(evt) {
  evt.preventDefault();
  const { target } = evt;
  const current = qs('.button-filter.is-active');
  current.addEventListener('click', projectsButtonFilterListener);
  current.classList.remove('is-active');

  target.removeEventListener('click', projectsButtonFilterListener);
  target.classList.add('is-active');

  projectsFilterBySegment(parseInt(target.dataset.segment, 10));
}

async function projectsInit() {
  infiniteScrollProjects(window.projects);

  // Set filters
  const btns = qsAll('.button-filter:not(.is-active)');
  btns.forEach((elem) => {
    elem.addEventListener('click', projectsButtonFilterListener);
  });

  const select = qs('select[name=categories]');
  select.addEventListener('change', function (evt) {
    evt.preventDefault();
    projectsFilterBySegment(parseInt(evt.target.value, 10));
  });
}

export default projectsInit;
