import { qs, qsAll } from './utils';

function formInvalidListener(evt) {
  const labels = qsAll('.wpcf7-not-valid-tip');
  // By default, the invalid labels will be siblings to the inputs
  // However, this will break the decoration!
  labels.forEach((label) => {
    const parent = label.parentElement.parentElement;
    parent.appendChild(label);
  });
}

function contactFormInit() {
  const wpcf7Elm = qs('.wpcf7');

  if (wpcf7Elm) {
    wpcf7Elm.addEventListener('wpcf7invalid', formInvalidListener, false);
  }
}

export default contactFormInit;
