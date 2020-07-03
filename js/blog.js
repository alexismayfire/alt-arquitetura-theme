async function restRequest(endpoint, callback) {
  const req = await fetch(`${restApi.url}${endpoint}`);
  return await req.json().then((res) => callback(res));
}

function getPostsInfo(res) {
  console.log(res);
  return res.map((post) => ({
    id: post['id'],
    imgSrc: post['featured_image_src'][0],
    title: post['title']['rendered'],
    date: post['formatted_date'],
    categories: post['categories_details'],
    author: post['author_name'],
    excerpt: post['excerpt']['rendered'],
    permalink: post['link'],
  }));
}

function createCard(post) {
  const elem = document.createElement('div');
  elem.classList.add('blog-card', 'fade-out');
  elem.dataset.id = post.id;

  const imgAnchor = document.createElement('a');
  imgAnchor.setAttribute('href', post.permalink);
  const img = document.createElement('img');
  img.classList.add('blog-card-image');
  img.setAttribute('src', post.imgSrc);
  img.setAttribute('alt', post.title);
  imgAnchor.appendChild(img);
  elem.appendChild(imgAnchor);

  const content = document.createElement('div');
  content.classList.add('blog-card-content');
  const titleLink = document.createElement('a');
  titleLink.classList.add('is-block');
  titleLink.setAttribute('href', post.permalink);
  const title = document.createElement('h4');
  title.classList.add('blog-card-title');
  title.innerHTML = post.title;
  titleLink.appendChild(title);
  content.appendChild(titleLink);

  const metaDate = document.createElement('span');
  metaDate.innerHTML = post.date;
  const metaAuthor = document.createElement('span');
  metaAuthor.classList.add('has-text-weight-semibold');
  metaAuthor.innerHTML = post.author;
  content.appendChild(metaDate);
  content.innerHTML += ' | ';
  content.appendChild(metaAuthor);

  const metaCategories = document.createElement('span');
  metaCategories.classList.add('is-block', 'has-text-weight-semibold', 'mb-4');
  post.categories.forEach((cat, index, arr) => {
    const node = document.createElement('a');
    node.setAttribute('href', cat.permalink);
    node.text = cat.name;
    metaCategories.appendChild(node);
    if (index + 1 < arr.length) {
      metaCategories.innerHTML += ', ';
    }
  });
  content.appendChild(metaCategories);

  const excerpt = document.createElement('div');
  excerpt.classList.add('blog-card-excerpt');
  excerpt.innerHTML = post.excerpt;
  const link = document.createElement('a');
  link.classList.add(
    'button',
    'is-dark',
    'is-uppercase',
    'has-text-weight-bold',
  );
  link.setAttribute('href', post.permalink);
  link.text = 'Ler mais';
  excerpt.appendChild(link);
  content.appendChild(excerpt);
  elem.appendChild(content);

  return elem;
}

async function getPosts() {
  const fields = [
    'id',
    'title',
    'formatted_date',
    'categories_details',
    'author_name',
    'excerpt',
    'link',
    'featured_media',
    'featured_image_src',
    'type',
  ].join('&_fields[]=');
  let url = `wp/v2/posts?per_page=100&_fields[]=${fields}`;
  const catId = document.querySelector('[data-category-id]');
  if (catId) {
    url += `&categories[]=${catId.dataset.categoryId}`;
  }
  const posts = await restRequest(url, getPostsInfo);
  window.allPosts = posts;
}

function infiniteScrollPosts(posts) {
  const wrapper = document.querySelector('.blog');
  const options = { threshold: 0.75 };
  window.observer = new IntersectionObserver(callback, options);

  let size = document.querySelectorAll('.blog-card').length;
  let last = document.querySelector('.blog-card:last-child');

  window.observer.observe(last, options);

  function callback(entries) {
    entries.forEach((entry) => {
      if (entry.isIntersecting && last.id === entry.target.id) {
        const postsToAdd = posts.slice(size, size + 2);

        // Adiciona os elementos ao DOM
        postsToAdd.forEach((post, index) => {
          const elem = createCard(post);
          wrapper.appendChild(elem);
        });

        // Anima a entrada de cada elemento
        setTimeout(function () {
          document.querySelectorAll('.blog-card.fade-out').forEach((elem) => {
            elem.classList.add('fade-in');
            setTimeout(function () {
              elem.classList.remove('fade-out');
            }, 250);
          });
        }, 250);

        // Atualização do osberver para o último elemento
        size = document.querySelectorAll('.blog-card').length;
        window.observer.unobserve(last);
        if (size < posts.length) {
          last = document.querySelector('.blog-card:last-child');
          window.observer.observe(last, options);
        }
      }
    });
  }
}

async function postsInit() {
  await getPosts();
  const page = window.location.pathname;
  const path = page.split('/');
  let categoryName = null;
  if (path.length > 3 && path[1] === 'blog') {
    const current = document.querySelector('.current-cat');
    categoryName = current.firstChild.text;
  }

  const filteredPosts = categoryName
    ? window.allPosts.filter((post) =>
        post.categories.some((cat) => cat.name === categoryName),
      )
    : window.allPosts;
  infiniteScrollPosts(filteredPosts);
}

document.addEventListener('DOMContentLoaded', postsInit);
