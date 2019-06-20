// media
const bkpDsk = '(min-width: 1024px)';
const bkpMbl = '(max-width: 1023.98px)';
const mdaHvr = '(hover: hover)';

const $id = function(id) {
  return document.getElementById(id);
};

// scrollToTop
const scrollToTop = function() {
  const t = document.documentElement.scrollTop || document.body.scrollTop;
  if (t > 0) {
    window.scrollTo(0, t - t / 8);
    window.requestAnimationFrame(scrollToTop);
  }
};

// scrollDown
const scrollToNext = function() {
  const t = document.documentElement.scrollTop || document.body.scrollTop;

  if (t < $id('scrollDownAnchor').offsetTop) {
    window.scrollTo(0, t + Math.ceil(($id('scrollDownAnchor').offsetTop - t) / 8));
    window.requestAnimationFrame(scrollToNext);
  }
};

window.addEventListener('load', function() {
  $id('goTopBtn').addEventListener('click', function() {
    scrollToTop();
  });

  $id('scrollDownBtn').addEventListener('click', function() {
    scrollToNext();
  });

  $id('menuToggle').addEventListener('click', function() {
    $id('commonHeader').classList.toggle('nav-menu-open');
  });
});
