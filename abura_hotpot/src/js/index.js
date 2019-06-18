// vue
const vm = new Vue({
  delimiters: ["<%", "%>"],
  el: "#mainContainer",
  data: { s1Data }
});

// swiper
const s1Swiper = new Swiper(".s1_swiper-container", {});
