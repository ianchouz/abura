"use strict";var bkpDsk="(min-width: 1024px)",bkpMbl="(max-width: 1023.98px)",mdaHvr="(hover: hover)",$id=function(id){return document.getElementById(id)},nowScrollTop=void 0,lastScrollTop=void 0,scrollCloseNavMenu=function scrollCloseNavMenu(){lastScrollTop=nowScrollTop,nowScrollTop=document.documentElement.scrollTop||document.body.scrollTop,lastScrollTop!==nowScrollTop&&($id("commonHeader").classList.remove("nav-menu-open"),window.removeEventListener("scroll",scrollCloseNavMenu))},vueMixins={delimiters:["<%","%>"],data:{targetOffsetTop:0},computed:{windowSize:function(){return window.matchMedia(bkpDsk).matches?"dsk":"mbl"}},created:function(){var _this=this;window.addEventListener("resize",_.debounce(function(){var wsz=window.matchMedia(bkpDsk).matches?"dsk":"mbl";_this.windowSize!==wsz&&location.reload()},150))},mounted:function(){changeLogo()},methods:{showNavMenu:function(){$id("commonHeader").classList.contains("nav-menu-open")?($id("commonHeader").classList.remove("nav-menu-open"),window.removeEventListener("scroll",scrollCloseNavMenu)):($id("commonHeader").classList.add("nav-menu-open"),window.addEventListener("scroll",scrollCloseNavMenu))},scrollToTop:function(){var t=document.documentElement.scrollTop||document.body.scrollTop;0<t&&(window.scrollTo(0,t-t/8),window.requestAnimationFrame(this.scrollToTop))},scrollToNext:function(){var t=document.documentElement.scrollTop||document.body.scrollTop;t<$id("scrollDownAnchor").offsetTop&&(window.scrollTo(0,t+Math.ceil(($id("scrollDownAnchor").offsetTop-t)/8)),window.requestAnimationFrame(this.scrollToNext))},setTargetOffsetTop:function(target){if(this.targetOffsetTop=$id(target).getBoundingClientRect().top,$id("commonHeader").classList.remove("nav-menu-open"),window.removeEventListener("scroll",scrollCloseNavMenu),window.matchMedia(bkpDsk).matches)this.goToSection();else{var t=document.documentElement.scrollTop||document.body.scrollTop;window.scrollTo(0,t+this.targetOffsetTop)}},goToSection:function(){var t=document.documentElement.scrollTop||document.body.scrollTop;0!==this.targetOffsetTop&&(window.scrollTo(0,t+Math.ceil(this.targetOffsetTop/8)),this.targetOffsetTop-=Math.ceil(this.targetOffsetTop/8),window.requestAnimationFrame(this.goToSection))},nowrap:function(value){return value.replace("<br />"," ")}}},changeLogo=function(){(document.documentElement.scrollTop||document.body.scrollTop)>$id("storyAnchor").offsetTop?$id("logo").classList.add("scroll"):$id("logo").classList.remove("scroll")};window.addEventListener("scroll",function(){changeLogo()}),window.matchMedia(bkpDsk).matches?window.addEventListener("load",function(){$id("fadeInMask").classList.add("loaded")}):document.addEventListener("DOMContentLoaded",function(){$id("fadeInMask").classList.add("loaded")});
//# sourceMappingURL=common-babel.js.map