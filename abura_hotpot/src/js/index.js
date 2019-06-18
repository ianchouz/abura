// vue
const vm = new Vue({
  delimiters: ['<%', '%>'],
  el: '#mainContainer',
  data: { s1Data, s2Data }
});

// s1
// swiper
const s1Swiper = new Swiper('.s1__swiper-container', {
  // Disable preloading of all images
  preloadImages: false,
  lazy: {
    loadPrevNext: true
  },
  pagination: {
    el: '.s1__swiper-pagination',
    type: 'bullets',
    clickable: true
  }
});

// s2
// row1
// swiper
const s2_1Swiper = new Swiper('.s2-1__swiper-container', {
  // Disable preloading of all images
  preloadImages: false,
  lazy: {
    loadPrevNext: true
  },
  pagination: {
    el: '.s2-1__swiper-pagination',
    type: 'bullets',
    clickable: true
  }
});
