// media
const bkpDsk = '(min-width: 1024px)';
const bkpMbl = '(max-width: 1023.98px)';
const mdaHvr = '(hover: hover)';

const $id = function(id) {
  return document.getElementById(id);
};

const vueMixins = {
  delimiters: ['<%', '%>'],
  data: {
    targetOffsetTop: 0
  },
  methods: {
    showNavMenu() {
      $id('commonHeader').classList.toggle('nav-menu-open');
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
        window.scrollTo(0, t + Math.ceil(($id('scrollDownAnchor').offsetTop - t) / 8));
        window.requestAnimationFrame(this.scrollToNext);
      }
    },
    setTargetOffsetTop(target) {
      this.targetOffsetTop = $id(target).getBoundingClientRect().top;
      // console.log(this.targetOffsetTop);

      this.goToSection();
    },
    goToSection() {
      const t = document.documentElement.scrollTop || document.body.scrollTop;

      if (this.targetOffsetTop !== 0) {
        window.scrollTo(0, t + Math.ceil(this.targetOffsetTop / 8));
        this.targetOffsetTop -= Math.ceil(this.targetOffsetTop / 8);
        window.requestAnimationFrame(this.goToSection);
      }
    }
  }
};
