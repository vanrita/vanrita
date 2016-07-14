var date = "2012-02-15";
var dateLate = "2070-12-12";
var today = now();
//==================== Nav Menu ========================//
$(window).scroll(function() {
    if ($(".navbar").offset().top > 150) {
        $(".navbar-fixed-top").addClass("top-nav-collapse");
    } else {
        $(".navbar-fixed-top").removeClass("top-nav-collapse");
    }
});

//==================== Smooth Page Scroll ========================//
//jQuery for page scrolling feature - requires jQuery Easing plugin
$(function() {
    $('.page-scroll a').bind('click', function(event) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: $($anchor.attr('href')).offset().top
        }, 1500, 'easeInOutExpo');
        event.preventDefault();
    });
});

//==================== Fullscreen Background slider ========================//
jQuery(function($){
	$.supersized({
	// Functionality
	slide_interval          :   5000,		// Length between transitions
	transition              :   1,		// 0-None, 1-Fade, 2-Slide Top, 3-Slide Right, 4-Slide Bottom, 5-Slide Left, 6-Carousel Right, 7-Carousel Left
	transition_speed		:	1000,		// Speed of transition
  
	// Components							
	slide_links				:	'blank',	// Individual links for each slide (Options: false, 'num', 'name', 'blank')
	slides					:   [			// Slideshow Images
										{image : 'images/bg/1.jpg'},
										{image : 'images/bg/2.jpg'},
										{image : 'images/bg/3.jpg'}
								]
	});
});

//==================== Intro Text Slider ========================//
$(document).ready(function(){
	$('#intro-slider').flexslider({
		animation: "fade",
		controlNav: false,
		DirectionNav: false,
		slideshowSpeed: 4000,
		animationSpeed: 600
	});
});

//==================== Testimonials Slider ========================//
$(document).ready(function(){
	$('#quote-slider').flexslider({
		animation: "slide",
		controlNav: "thumbnails",
		DirectionNav: "true"
	});
});

//==================== Project Slider ========================//
$(document).ready(function(){
	$('#project-slider').flexslider({
		animation: "slide",
		controlNav: "false",
		DirectionNav: "true"
	});
});

//==================== Animated Facts ========================//
jQuery(document).ready(function($) {
  "use strict";
	var days = DateDiff(date,today);
	var daysLeft = DateDiff(today,dateLate);
	$('.facts-content').appear(function() {
	$('#lines').animateNumber({ number: 2 }, 2000 );
	$('#lines1').animateNumber({ number: 1084 }, 2000 );
	$('#lines2').animateNumber({ number: days }, 2000 );
	$('#lines3').animateNumber({ number: daysLeft }, 2000 );
	},{accX: 0, accY: -50});
});


//==================== Portfolio ========================//
$(function () {
	var filterList = {
		init: function () {
			// MixItUp plugin
			// http://mixitup.io
			$('#portfoliolist').mixitup({
				targetSelector: '.portfolio',
				filterSelector: '.filter',
				effects: ['fade'],
				easing: 'snap',
				// call the hover effect
				onMixEnd: filterList.hoverEffect()
			});			
		},
		hoverEffect: function () {
		}
	};
	// Run the show!
	filterList.init();
});

//==================== Parallax ========================//
jQuery(document).ready(function ($) {

    $.stellar({
		horizontalOffset: 50
    });

    var links = $('.navigation').find('li');
    slide = $('.slide');
    button = $('.button');
    mywindow = $(window);
    htmlbody = $('html,body');

    function goToByScroll(dataslide) {
        htmlbody.animate({
            scrollTop: $('.slide[data-slide="' + dataslide + '"]').offset().top
        }, 2000, 'easeInOutQuint');
    }

});

//==================== 获取当前日期 ========================//
 function now(){
   var mydate = new Date();
   var str = "" + mydate.getFullYear() + "-";
   str += (mydate.getMonth()+1) + "-";
   str += mydate.getDate();
   return str;
 }


//==================== 时间差计算 ========================//
function DateDiff(sDate1, sDate2) {  //sDate1和sDate2是yyyy-MM-dd格式 
    var aDate, oDate1, oDate2, iDays;
    aDate = sDate1.split("-");
    oDate1 = new Date(aDate[1] + '-' + aDate[2] + '-' + aDate[0]);  //转换为yyyy-MM-dd格式
    aDate = sDate2.split("-");
    oDate2 = new Date(aDate[1] + '-' + aDate[2] + '-' + aDate[0]);
    iDays = parseInt(Math.abs(oDate1 - oDate2) / 1000 / 60 / 60 / 24); //把相差的毫秒数转换为天数
 
    return iDays;  //返回相差天数
}
//==================== 下拉菜单 =========================//
(function(){
    $('#header').on('mouseenter', '#user', function(){
        $(this).find('.drop-list').slideDown('fast');
    }).on('mouseleave', '#user', function(){
        $(this).find('.drop-list').slideUp('fast');
    })
})(jQuery, this);
