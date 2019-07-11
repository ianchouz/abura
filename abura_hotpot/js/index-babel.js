"use strict";function _toConsumableArray(arr){if(Array.isArray(arr)){for(var i=0,arr2=Array(arr.length);i<arr.length;i++)arr2[i]=arr[i];return arr2}return Array.from(arr)}var vm=new Vue({el:"#mainContainer",data:{footerData:footerData,s1Data:s1Data,s2Data:s2Data,s2row2ImgIndex:0,s2row3SwiperIndex:0,s3Data:s3Data,s4Data:s4Data,s5Data:s5Data,newsIndex:0},mixins:[vueMixins],methods:{hoverChangeImg:function(index){this.s2row2ImgIndex=index},hoverChangeSet:function(e,index){this.s2row3SwiperIndex=index,setTimeout(function(){vm.setTargetLeft()},0)},hoverChangeNewsIndex:function(index){this.newsIndex=index},setTargetLeft:function(){var targetLeft=document.querySelector(".set-item.active").getBoundingClientRect().left;$id("s2_3BorderDecoration").style.left=targetLeft+"px"},showPopup:function(popupType){if("menu"===popupType){$id("menuPopup").classList.add("active");var nowSwiper=$id("menuPopup").querySelector(".popup-header__swiper-container").swiper,nowImgs=this.s2Data.row2.menus[this.s2row2ImgIndex].imgs;this.changePopupSlidesImg(nowSwiper,nowImgs)}else if("set"===popupType){$id("setPopup").classList.add("active");var _nowSwiper=$id("setPopup").querySelector(".popup-header__swiper-container").swiper,_nowImgs=this.s2Data.row3.swiper[this.s2row3SwiperIndex].imgs;this.changePopupSlidesImg(_nowSwiper,_nowImgs)}else"news"===popupType&&$id("newsPopup").classList.add("active");document.documentElement.classList.add("hide-scrollbar")},changePopupSlidesImg:function(theSwiper,theImgs){theSwiper.update(),theSwiper.slideTo(0,0),[].concat(_toConsumableArray(theSwiper.slides)).forEach(function(item,index){item.querySelector("img").className="lazyload",item.querySelector("img").dataset.src=theImgs[index]})}},computed:{s5DataNewsSwitch:function(){return window.matchMedia(bkpDsk).matches?this.s5Data.news:this.s5Data.news.slice(0,10)}},mounted:function(){window.matchMedia(bkpDsk).matches?document.querySelectorAll(".js-clamp").forEach(function(item){$clamp(item,{clamp:2})}):document.querySelectorAll(".js-clamp").forEach(function(item){$clamp(item,{clamp:4})})}});if(!s1Data.video.active||window.matchMedia(bkpMbl).matches)var s1Swiper=new Swiper(".s1__swiper-container",{preloadImages:!1,lazy:{loadPrevNext:!0},pagination:{el:".s1__swiper-pagination",type:"bullets",clickable:!0},effect:"fade",autoplay:{delay:8e3,disableOnInteraction:!1},speed:800});var s2_1Swiper=new Swiper(".s2-1__swiper-container",{preloadImages:!1,lazy:{loadPrevNext:!0},pagination:{el:".s2-1__swiper-pagination",type:"bullets",clickable:!0},speed:1e3,on:{slidePrevTransitionStart:function(){this.slides[this.previousIndex].classList.remove("prev"),this.slides[this.activeIndex].classList.add("prev")}}});if(window.matchMedia(bkpMbl).matches)var s2_2Swiper=new Swiper(".s2-2__swiper-container",{preloadImages:!1,lazy:{loadPrevNext:!0},navigation:{nextEl:".s2-2__swiper-button-next",prevEl:".s2-2__swiper-button-prev"},effect:"fade",speed:800,on:{init:function(){vm.s2row2ImgIndex=this.activeIndex},slideChange:function(){vm.s2row2ImgIndex=this.activeIndex}}});var s2_3ParamDsk={preloadImages:!1,lazy:{loadPrevNext:!0},navigation:{prevEl:".s2-3__swiper-button-prev",nextEl:".s2-3__swiper-button-next"},slidesPerView:5,breakpoints:{1400:{slidesPerView:4},1200:{slidesPerView:3}}},s2_3ParamMbl={init:!1,preloadImages:!1,lazy:{loadPrevNext:!0},navigation:{prevEl:".s2-3__swiper-button-prev",nextEl:".s2-3__swiper-button-next"},pagination:{el:".s2-3__swiper-pagination",type:"fraction"},slidesPerView:"auto",centeredSlides:!0,loop:!0,loopedSlides:5};if(window.matchMedia(bkpDsk).matches)var s2_3Swiper=new Swiper(".s2-3__swiper-container",s2_3ParamDsk);else{var _s2_3Swiper=new Swiper(".s2-3__swiper-container",s2_3ParamMbl),s2_3SlideChange=function(){[document.querySelector(".s2-3__swiper-pagination .swiper-pagination-current"),document.querySelector(".s2-3__swiper-pagination .swiper-pagination-total")].forEach(function(item){1===item.innerText.length&&(item.innerText=0+item.innerText)}),vm.s2row3SwiperIndex=_s2_3Swiper.realIndex};_s2_3Swiper.on("init",function(){s2_3SlideChange(),document.querySelectorAll(".swiper-slide-duplicate").forEach(function(item){item.addEventListener("click",function(){vm.showPopup("set")})})}),_s2_3Swiper.init(),_s2_3Swiper.on("slideChange",function(){s2_3SlideChange()})}var pageClockOffsetTop=0,s3Swiper=new Swiper(".s3__swiper-container",{preloadImages:!1,lazy:{loadPrevNext:!0},pagination:{el:".s3__swiper-pagination",type:"bullets",clickable:!0},navigation:{nextEl:".s3__swiper-button-next",prevEl:".s3__swiper-button-prev"},effect:"fade",autoplay:{delay:3e3,disableOnInteraction:!1},speed:800,on:{init:function(){pageClockOffsetTop=$id("pageClock").children[this.activeIndex].offsetTop,$id("pageClock").style.transform="translateY(-"+pageClockOffsetTop+"px)"},slideChange:function(){pageClockOffsetTop=$id("pageClock").children[this.activeIndex].offsetTop,$id("pageClock").style.transform="translateY(-"+pageClockOffsetTop+"px)"}}}),s4Swiper=new Swiper(".s4__swiper-container",{preloadImages:!1,lazy:{loadPrevNext:!0},navigation:{nextEl:".s4__swiper-button-next",prevEl:".s4__swiper-button-prev"},slidesPerView:"auto",loop:!0,loopedSlides:4,centeredSlides:!0,speed:800});if(document.querySelectorAll(".js-scrollbar").forEach(function(item){new PerfectScrollbar(item,{wheelSpeed:.5,wheelPropagation:!1})}),window.matchMedia(bkpMbl).matches)var s5Swiper=new Swiper(".s5__swiper-container",{preloadImages:!1,lazy:{loadPrevNext:!0},pagination:{el:".s5__swiper-pagination",type:"bullets"},spaceBetween:100,on:{init:function(){vm.newsIndex=this.activeIndex},slideChange:function(){vm.newsIndex=this.activeIndex}}});var popupSwiper=new Swiper(".popup-header__swiper-container",{pagination:{el:".popup-header__swiper-pagination",type:"bullets",clickable:!0}});if(window.matchMedia(bkpDsk).matches){var controller=new ScrollMagic.Controller({}),s1Tl=new TimelineMax({});s1Tl.to(".section2__row-1 .main-title-wrapper",.001,{className:"+=show"}).to("#scrollDownBtn",.001,{className:"+=hide"}),new ScrollMagic.Scene({triggerElement:"#storyAnchor",triggerHook:.8}).setTween(s1Tl).addTo(controller);var s2Tl=new TimelineMax({});s2Tl.fromTo(".bg-decoration-1 .img",1,{yPercent:5},{yPercent:-5},0).fromTo(".bg-decoration-2 .img",1,{yPercent:-5},{yPercent:5},0),new ScrollMagic.Scene({duration:"200%",triggerElement:"#storyAnchor",triggerHook:1}).setTween(s2Tl).addTo(controller);var s4Tl=new TimelineMax({});s4Tl.fromTo(".bg-decoration-3 .img-2",1,{yPercent:10},{yPercent:-10},0),new ScrollMagic.Scene({duration:"200%",triggerElement:"#recommendAnchor",triggerHook:1}).setTween(s4Tl).addTo(controller);var footerTl=new TimelineMax({});footerTl.fromTo(".bg-decoration-4-1 .img-2",1,{yPercent:10},{yPercent:0},0),new ScrollMagic.Scene({duration:400+$id("commonFooter").offsetHeight,triggerElement:"#contactAnchor",triggerHook:1,offset:-400}).setTween(footerTl).addTo(controller)}
//# sourceMappingURL=index-babel.js.map