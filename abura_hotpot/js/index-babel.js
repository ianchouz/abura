"use strict";var vm=new Vue({delimiters:["<%","%>"],el:"#mainContainer",data:{s1Data:s1Data,s2Data:s2Data,s2row2ImgIndex:0,s2row3SwiperIndex:0,s3Data:s3Data,s4Data:s4Data,s5Data:s5Data},methods:{hoverChangeImg:function(index){this.s2row2ImgIndex=index},hoverChangeSet:function(e,index){this.s2row3SwiperIndex=index,setTimeout(function(){vm.setTargetLeft()},0)},setTargetLeft:function(){var targetLeft=document.querySelector(".set-item.active").getBoundingClientRect().left;$id("s2_3BorderDecoration").style.left=targetLeft+"px"}}}),s1Swiper=new Swiper(".s1__swiper-container",{preloadImages:!1,lazy:{loadPrevNext:!0},pagination:{el:".s1__swiper-pagination",type:"bullets",clickable:!0}}),s2_1Swiper=new Swiper(".s2-1__swiper-container",{preloadImages:!1,lazy:{loadPrevNext:!0},pagination:{el:".s2-1__swiper-pagination",type:"bullets",clickable:!0}}),ps=new PerfectScrollbar("#js-scrollbar"),s2_3Swiper=new Swiper(".s2-3__swiper-container",{preloadImages:!1,lazy:{loadPrevNext:!0},navigation:{prevEl:".s2-3__swiper-button-prev",nextEl:".s2-3__swiper-button-next"},slidesPerView:5}),s3Swiper=new Swiper(".s3__swiper-container",{preloadImages:!1,lazy:{loadPrevNext:!0},pagination:{el:".s3__swiper-pagination",type:"bullets",clickable:!0}}),s4Swiper=new Swiper(".s4__swiper-container",{preloadImages:!1,lazy:{loadPrevNext:!0},navigation:{nextEl:".s4__swiper-button-next",prevEl:".s4__swiper-button-prev"},slidesPerView:"auto",loop:!0,loopedSlides:4,centeredSlides:!0});