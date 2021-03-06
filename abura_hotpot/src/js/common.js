// media
const bkpDsk = '(min-width: 1024px)';
const bkpMbl = '(max-width: 1023.98px)';
const mdaHvr = '(hover: hover)';

const $id = function(id) {
  return document.getElementById(id);
};

let nowScrollTop, lastScrollTop;

const scrollCloseNavMenu = function() {
  lastScrollTop = nowScrollTop;
  nowScrollTop = document.documentElement.scrollTop || document.body.scrollTop;

  if (lastScrollTop !== nowScrollTop) {
    $id('commonHeader').classList.remove('nav-menu-open');
    window.removeEventListener('scroll', scrollCloseNavMenu);
  }
};

const vueMixins = {
  delimiters: ['<%', '%>'],
  data: {
    targetOffsetTop: 0
  },
  computed: {
    // windowSize
    windowSize() {
      return window.matchMedia(bkpDsk).matches ? 'dsk' : 'mbl';
    }
  },

  created() {
    // windowSize
    window.addEventListener(
      'resize',
      _.debounce(() => {
        let wsz = window.matchMedia(bkpDsk).matches ? 'dsk' : 'mbl';
        if (this.windowSize !== wsz) {
          location.reload();
        }
      }, 150)
    );
  },

  mounted() {
    // console.log('mounted');
    changeLogo();
    // $id('fadeInMask').classList.add('loaded');
  },
  methods: {
    showNavMenu() {
      // $id('commonHeader').classList.toggle('nav-menu-open');

      if (!$id('commonHeader').classList.contains('nav-menu-open')) {
        $id('commonHeader').classList.add('nav-menu-open');
        window.addEventListener('scroll', scrollCloseNavMenu);
      } else {
        $id('commonHeader').classList.remove('nav-menu-open');
        window.removeEventListener('scroll', scrollCloseNavMenu);
      }
    },
    scrollToTop() {
      const t = document.documentElement.scrollTop || document.body.scrollTop;
      if (t > 0) {
        window.scrollTo(0, t - t / 8);
        window.requestAnimationFrame(this.scrollToTop);
      }
    },
    scrollToNext() {
      const t = document.documentElement.scrollTop || document.body.scrollTop;

      if (t < $id('scrollDownAnchor').offsetTop) {
        window.scrollTo(
          0,
          t + Math.ceil(($id('scrollDownAnchor').offsetTop - t) / 8)
        );
        window.requestAnimationFrame(this.scrollToNext);
      }
    },
    setTargetOffsetTop(target) {
      this.targetOffsetTop = $id(target).getBoundingClientRect().top;
      // console.log(this.targetOffsetTop);
      $id('commonHeader').classList.remove('nav-menu-open');
      window.removeEventListener('scroll', scrollCloseNavMenu);

      if (window.matchMedia(bkpDsk).matches) {
        this.goToSection();
      } else {
        const t = document.documentElement.scrollTop || document.body.scrollTop;
        window.scrollTo(0, t + this.targetOffsetTop);
      }
    },
    goToSection() {
      const t = document.documentElement.scrollTop || document.body.scrollTop;

      if (this.targetOffsetTop !== 0) {
        window.scrollTo(0, t + Math.ceil(this.targetOffsetTop / 8));
        this.targetOffsetTop -= Math.ceil(this.targetOffsetTop / 8);
        window.requestAnimationFrame(this.goToSection);
      }
    },
    nowrap(value) {
      // console.log(value);
      return value.replace('<br />', ' ');
    }
  }
};

// logo
const changeLogo = function() {
  const t = document.documentElement.scrollTop || document.body.scrollTop;
  // console.log(t);

  if (t > $id('storyAnchor').offsetTop) {
    $id('logo').classList.add('scroll');
  } else {
    $id('logo').classList.remove('scroll');
  }
};

window.addEventListener('scroll', function() {
  changeLogo();
});

// 開場
document.addEventListener('DOMContentLoaded', () => {
  // console.log('DOMContentLoaded');
  // $id('fadeInMask').classList.add('loaded');
  setTimeout(() => {
    $id('loading').classList.add('loaded');
  }, 3300);
});
