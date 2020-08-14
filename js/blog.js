import { qs, qsAll } from './utils';

function createCategories(categories) {
  return categories
    .map((cat) => `<a href="${cat.permalink}">${cat.name}</a>`)
    .join(', ');
}

function createCard(post) {
  const elem = document.createElement('div');
  elem.classList.add('blog-card', 'fade-out');
  elem.dataset.id = post.id;

  const html = `
    <a href="${post.permalink}">
      <img class="blog-card-image" src="${post.imgSrc}" alt="${post.title}" />
    </a>
    <div class="blog-card-content">
      <a class="is-block" href="${post.permalink}">
        <h4 class="blog-card-title">${post.title}</h4>
      </a>
      <span>${post.date}</span> | 
      <span class="has-text-weight-semibold">${post.author}</span>
      <span class="is-block has-text-weight-semibold mb-4">
        ${createCategories(post.categories)}
      </span>
      <div class="blog-card-excerpt">
        ${post.excerpt}
        <a class="button is-dark is-uppercase has-text-weight-bold" href="${
          post.permalink
        }">
          Ler Mais
        </a>
      </div>
    </div>
  `;

  elem.innerHTML = html.trim();

  return elem;
}

function infiniteScrollPosts() {
  const wrapper = qs('.blog');
  const options = { threshold: 0.75 };
  window.observer = new IntersectionObserver(callback, options);

  let size = qsAll('.blog-card').length;
  let last = qs('.blog-card:last-child');

  window.observer.observe(last, options);

  function callback(entries) {
    entries.forEach((entry) => {
      if (entry.isIntersecting && last.id === entry.target.id) {
        const postsToAdd = window.posts.slice(size, size + 2);

        // Adiciona os elementos ao DOM
        postsToAdd.forEach((post) => wrapper.appendChild(createCard(post)));

        // Anima a entrada de cada elemento
        setTimeout(function () {
          qsAll('.blog-card.fade-out').forEach((elem) => {
            elem.classList.add('fade-in');
            setTimeout(function () {
              elem.classList.remove('fade-out');
            }, 250);
          });
        }, 250);

        // Atualização do observer para o último elemento
        size = qsAll('.blog-card').length;
        window.observer.unobserve(last);
        if (size < window.posts.length) {
          last = qs('.blog-card:last-child');
          window.observer.observe(last, options);
        }
      }
    });
  }
}

export default infiniteScrollPosts;
