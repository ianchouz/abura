// vue
const vm = new Vue({
  delimiters: ['<%', '%>'],
  el: '#mainContainer',
  data: { s1Data, s2Data, s2row2ImgIndex: 0, s2row3SwiperIndex: 0, s3Data, s4Data, s5Data },
  methods: {
    hoverChangeImg(index) {
      // console.log(index);
      this.s2row2ImgIndex = index;
    },
    hoverChangeSet(e, index) {
      this.s2row3SwiperIndex = index;

      setTimeout(() => {
        vm.setTargetLeft();
      }, 0);
    },
    setTargetLeft() {
      const targetLeft = document.querySelector('.set-item.active').getBoundingClientRect().left;
      $id('s2_3BorderDecoration').style.left = `${targetLeft}px`;
    }
  }
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

// row2
const ps = new PerfectScrollbar('#js-scrollbar');

// row3
// swiper
const s2_3Swiper = new Swiper('.s2-3__swiper-container', {
  // Disable preloading of all images
  preloadImages: false,
  lazy: {
    loadPrevNext: true
  },
  navigation: {
    prevEl: '.s2-3__swiper-button-prev',
    nextEl: '.s2-3__swiper-button-next'
  },
  slidesPerView: 5
});

// s3
// swiper
const s3Swiper = new Swiper('.s3__swiper-container', {
  // Disable preloading of all images
  preloadImages: false,
  lazy: {
    loadPrevNext: true
  },
  pagination: {
    el: '.s3__swiper-pagination',
    type: 'bullets',
    clickable: true
  }
});

// s4
// swiper
const s4Swiper = new Swiper('.s4__swiper-container', {
  // Disable preloading of all images
  preloadImages: false,
  lazy: {
    loadPrevNext: true
  },
  navigation: {
    nextEl: '.s4__swiper-button-next',
    prevEl: '.s4__swiper-button-prev'
  },
  slidesPerView: 'auto',
  loop: true,
  loopedSlides: 4,
  centeredSlides: true
});
