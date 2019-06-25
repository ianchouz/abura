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

      document.documentElement.classList.add('hide-scrollbar');
    }
  },
  computed: {
    s5DataNewsSwitch() {
      if (window.matchMedia(bkpDsk).matches) {
        return this.s5Data.news;
      } else {
        return this.s5Data.news.slice(0, 10);
      }
    }
  },
  mounted() {
    if (window.matchMedia(bkpDsk).matches) {
      document.querySelectorAll('.js-clamp').forEach(item => {
        $clamp(item, { clamp: 2 });
      });
    } else {
      document.querySelectorAll('.js-clamp').forEach(item => {
        $clamp(item, { clamp: 4 });
      });
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

// row2
if (window.matchMedia(bkpMbl).matches) {
  const s2_2Swiper = new Swiper('.s2-2__swiper-container', {
    // Disable preloading of all images
    preloadImages: false,
    lazy: {
      loadPrevNext: true
    },
    navigation: {
      nextEl: '.s2-2__swiper-button-next',
      prevEl: '.s2-2__swiper-button-prev'
    },
    effect: 'fade',
    speed: 800,
    on: {
      init() {
        vm.s2row2ImgIndex = this.activeIndex;
      },
      slideChange() {
        vm.s2row2ImgIndex = this.activeIndex;
      }
    }
  });
}

// row3
// swiper
// dsk
const s2_3ParamDsk = {
  // Disable preloading of all images
  preloadImages: false,
  lazy: {
    loadPrevNext: true
  },
  navigation: {
    prevEl: '.s2-3__swiper-button-prev',
    nextEl: '.s2-3__swiper-button-next'
  },
  slidesPerView: 5,
  breakpoints: {
    // when window width is <= 1400px
    1400: {
      slidesPerView: 4
    },
    1200: {
      slidesPerView: 3
    }
  }
};
// mbl
const s2_3ParamMbl = {
  init: false,
  // Disable preloading of all images
  preloadImages: false,
  lazy: {
    loadPrevNext: true
  },
  navigation: {
    prevEl: '.s2-3__swiper-button-prev',
    nextEl: '.s2-3__swiper-button-next'
  },
  pagination: {
    el: '.s2-3__swiper-pagination',
    type: 'fraction'
  },
  slidesPerView: 'auto',
  centeredSlides: true,
  loop: true,
  loopedSlides: 5
};

if (window.matchMedia(bkpDsk).matches) {
  // dsk
  const s2_3Swiper = new Swiper('.s2-3__swiper-container', s2_3ParamDsk);
} else {
  // mbl
  const s2_3Swiper = new Swiper('.s2-3__swiper-container', s2_3ParamMbl);

  // ======================================
  const s2_3SlideChange = function() {
    const currentPagi = document.querySelector('.s2-3__swiper-pagination .swiper-pagination-current');
    const totalPagi = document.querySelector('.s2-3__swiper-pagination .swiper-pagination-total');

    const arr = [currentPagi, totalPagi];

    arr.forEach(item => {
      if (item.innerText.length === 1) {
        item.innerText = 0 + item.innerText;
      }
    });

    vm.s2row3SwiperIndex = s2_3Swiper.realIndex;
  };
  // ======================================

  s2_3Swiper.on('init', function() {
    s2_3SlideChange();

    // duplicate
    // console.log(document.querySelectorAll('.swiper-slide-duplicate'));
    const duplicateSlides = document.querySelectorAll('.swiper-slide-duplicate');
    duplicateSlides.forEach(item => {
      item.addEventListener('click', function() {
        vm.showPopup('set');
      });
    });
  });

  s2_3Swiper.init();

  s2_3Swiper.on('slideChange', function() {
    s2_3SlideChange();
  });
}

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
  navigation: {
    nextEl: '.s3__swiper-button-next',
    prevEl: '.s3__swiper-button-prev'
  },
  effect: 'fade',
  autoplay: {
    delay: 3000,
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
  centeredSlides: true,
  speed: 800
});

// scrollbar
// console.log(document.querySelectorAll('.js-scrollbar'));
document.querySelectorAll('.js-scrollbar').forEach(item => {
  new PerfectScrollbar(item, {
    wheelSpeed: 0.5,
    wheelPropagation: false
  });
});

// s5
if (window.matchMedia(bkpMbl).matches) {
  const s5Swiper = new Swiper('.s5__swiper-container', {
    // Disable preloading of all images
    preloadImages: false,
    lazy: {
      loadPrevNext: true
    },
    pagination: {
      el: '.s5__swiper-pagination',
      type: 'bullets'
      // dynamicBullets: true
    },
    spaceBetween: 100,
    // effect: 'fade',
    // speed: 800,
    on: {
      init() {
        vm.newsIndex = this.activeIndex;
      },
      slideChange() {
        vm.newsIndex = this.activeIndex;
      }
    }
  });
}

// scrollmagic

if (window.matchMedia(bkpDsk).matches) {
  const controller = new ScrollMagic.Controller({
    // addIndicators: true
  });

  // ===================== s1
  const s1Tl = new TimelineMax({});
  s1Tl.to('.section2__row-1 .main-title-wrapper', 0.001, { className: '+=show' }).to('#scrollDownBtn', 0.001, {
    className: '+=hide'
  });

  new ScrollMagic.Scene({
    triggerElement: '#storyAnchor',
    triggerHook: 0.8
  })
    .setTween(s1Tl)
    .addTo(controller);

  // ===================== s2
  const s2Tl = new TimelineMax({});
  s2Tl
    .fromTo('.bg-decoration-1 .img', 1, { yPercent: 5 }, { yPercent: -5 }, 0)
    .fromTo('.bg-decoration-2 .img', 1, { yPercent: -5 }, { yPercent: 5 }, 0);

  new ScrollMagic.Scene({
    duration: '200%',
    triggerElement: '#storyAnchor',
    triggerHook: 1
  })
    .setTween(s2Tl)
    .addTo(controller);
}
