// vue
const vm = new Vue({
  el: '#mainContainer',
  data: {
    footerData,
    s1Data,
    s2Data,
    s2row2ImgIndex: 0,
    s2row3SwiperIndex: 0,
    s3Data,
    s4Data,
    s5Data,
    newsIndex: 0
  },
  mixins: [vueMixins],
  methods: {
    hoverChangeImg(index) {
      // console.log(index);
      this.s2row2ImgIndex = index;
    },
    hoverChangeSet(e, index) {
      // console.log(index);
      this.s2row3SwiperIndex = index;

      setTimeout(() => {
        vm.setTargetLeft();
      }, 0);
    },
    hoverChangeNewsIndex(index) {
      // console.log(index);
      this.newsIndex = index;
    },
    setTargetLeft() {
      const targetLeft = document.querySelector('.set-item.active').getBoundingClientRect().left;
      $id('s2_3BorderDecoration').style.left = `${targetLeft}px`;
    },
    showPopup(popupType) {
      // console.log(popupType);
      if (popupType === 'menu') {
        $id('menuPopup').classList.add('active');
      } else if (popupType === 'set') {
        $id('setPopup').classList.add('active');
      } else if (popupType === 'news') {
        $id('newsPopup').classList.add('active');
      }
    }
  },
  mounted() {
    document.querySelectorAll('.js-clamp').forEach(item => {
      $clamp(item, { clamp: 2 });
    });
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
  },
  effect: 'fade',
  autoplay: {
    delay: 8000,
    disableOnInteraction: false
  },
  speed: 800
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
let pageClockOffsetTop = 0;
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
  },
  effect: 'fade',
  autoplay: {
    delay: 8000,
    disableOnInteraction: false
  },
  speed: 800,
  on: {
    init: function() {
      // console.log('swiper initialized');
      pageClockOffsetTop = $id('pageClock').children[this.activeIndex].offsetTop;
      $id('pageClock').style.transform = `translateY(-${pageClockOffsetTop}px)`;
    },
    slideChange: function() {
      // console.log('slideChange');
      pageClockOffsetTop = $id('pageClock').children[this.activeIndex].offsetTop;
      $id('pageClock').style.transform = `translateY(-${pageClockOffsetTop}px)`;
    }
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

// scrollbar
// console.log(document.querySelectorAll('.js-scrollbar'));
document.querySelectorAll('.js-scrollbar').forEach(item => {
  new PerfectScrollbar(item, {
    wheelSpeed: 0.5,
    wheelPropagation: false
  });
});
