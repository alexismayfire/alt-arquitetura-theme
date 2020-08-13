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
