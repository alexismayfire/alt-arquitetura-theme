async function projectFilterListener(evt) {
  evt.preventDefault();
  var current = document.querySelector('.projects-filters .menu-link.active');
  current.classList.remove('active');
  current.addEventListener('click', projectFilterListener);
  evt.target.classList.add('active');
  var cat = evt.target.dataset.filterCategory;
  var postData = 'action=myfilter&page=1&replace=1';
  if (cat) {
    postData += '&cat=' + cat;
  }
  await showProjects(postData, true);
  projectIndicatorElements();
}

async function projectIndicatorListener(evt) {
  evt.preventDefault();
  var current = document.querySelector('.indicator-item.active');
  current.classList.remove('active');
  current.addEventListener('click', projectIndicatorListener);
  evt.currentTarget.classList.add('active');
  var page = evt.currentTarget.dataset.page;
  var postData = 'action=myfilter&page=' + page;
  var category = document.querySelector('.projects-filters .menu-link.active')
    .dataset.filterCategory;
  if (category) {
    postData += '&cat=' + category;
  }
  await showProjects(postData);
}

function createProjectCarousel(wrapper, carousel, data, replace, loadTimeout) {
  if (replace) {
    carousel.parentElement.remove();
    var node = document.createElement('div');
    node.innerHTML = data;
    wrapper.appendChild(node);
    setTimeout(function () {
      var el = document.querySelector('.project-carousel');
      console.log(el);
      el.classList.remove('fade');
    }, 300);
  } else {
    carousel.innerHTML = data;
    carousel.classList.remove('fade');
  }

  projectFilterElements();
  projectIndicatorElements();
}

async function showProjects(postData, replace = false) {
  var wrapper = document.querySelector('.projects');
  var carousel = document.querySelector('.project-carousel');
  var carouselHeight = carousel.clientHeight;
  var currentData = carousel.innerHTML;
  var complete = false;
  var loadTimeout = false;
  var data = null;

  setTimeout(function () {
    if (!complete) {
      carousel.innerHTML =
        '<div style="height: ' + carouselHeight + 'px">Loading...</div>';
      loadTimeout = true;
    } else {
      createProjectCarousel(wrapper, carousel, data, replace);
    }
  }, 600);
  carousel.classList.add('fade');

  await fetch(ajax.url, {
    method: 'POST',
    body: new URLSearchParams(postData),
  }).then(async function (response) {
    complete = true;
    data = await response.text();
    if (loadTimeout) {
      createProjectCarousel(wrapper, carousel, data, replace, loadTimeout);
    }
  });
}

function projectFilterElements() {
  document
    .querySelectorAll('a[data-filter-category]:not(.active)')
    .forEach(function (elem) {
      elem.addEventListener('click', projectFilterListener);
    });
}

function projectIndicatorElements() {
  document
    .querySelectorAll('.indicator-item:not(.active)')
    .forEach(function (elem) {
      elem.addEventListener('click', projectIndicatorListener);
    });
}

document.addEventListener('DOMContentLoaded', function () {
  var elems = document.querySelectorAll('.sidenav');
  var instances = M.Sidenav.init(elems);

  elems = document.querySelectorAll('.scrollspy');
  instances = M.ScrollSpy.init(elems, { scrollOffset: 64 });

  document
    .querySelectorAll('.menu-item-object-custom')
    .forEach(function (elem) {
      var link = elem.firstChild.getAttribute('href');
      if (link.startsWith('#')) {
        elem.firstChild.setAttribute('href', '/' + link);
      }
    });

  /*
  elems = document.querySelectorAll('.carousel');
  instances = M.Carousel.init(elems, {
    indicators: true,
    numVisible: 1,
    dist: 0,
    fullWidth: true,
    noWrap: true,
  });
  */
});

projectFilterElements();
projectIndicatorElements();

function headerDecoration() {
  setTimeout(function () {
    document
      .querySelectorAll('.section-header-decoration-left')
      .forEach(function (elem) {
        var width = elem.previousElementSibling.querySelector('.header-text')
          .clientWidth;
        elem.querySelector('line').setAttribute('x2', width);
      });
  }, 0);
}

headerDecoration();
