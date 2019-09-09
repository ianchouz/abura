const $id = function(id) {
  return document.getElementById(id);
};

document.addEventListener('DOMContentLoaded', () => {
  // console.log('DOMContentLoaded');
  setTimeout(() => {
    $id('loading').classList.add('loaded');
  }, 2500);
});
