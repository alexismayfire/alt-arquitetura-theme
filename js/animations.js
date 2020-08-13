function innerAnimate(elem, type, classIn, classOut) {
  if (type === 'out') {
    elem.classList.remove('fade-in', `${classIn}-in`);
    elem.classList.add('fade-out', `${classOut}-out`);
  } else if (type === 'in') {
    elem.classList.add('fade-in', `${classIn}-in`);
    elem.classList.remove('fade-out', `${classOut}-out`);
  }
}

function animate(elem, type, classIn, classOut) {
  if (Array.isArray(elem) || elem.length) {
    elem.forEach((el) => innerAnimate(el, type, classIn, classOut));
  } else {
    innerAnimate(elem, type, classIn, classOut);
  }
}

const CLASSES = {
  scale: 'scale',
  slide: 'slide',
  slideLeft: 'slide-left',
  slideRight: 'slide-right',
};

const scale = (elem, type) => animate(elem, type, CLASSES.scale, CLASSES.scale);

const slideLeft = (elem, type) => {
  animate(elem, type, CLASSES.slide, CLASSES.slideLeft);
};

const slideRight = (elem, type) => {
  animate(elem, type, CLASSES.slide, CLASSES.slideRight);
};

export default {
  scale,
  slideLeft,
  slideRight,
};
