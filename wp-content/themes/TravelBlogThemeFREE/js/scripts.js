jQuery(document).ready(function($){
    $(window).scroll(function() {
        //console.log($(document).scrollTop());
        //alert($(document).scrollTop());
        if($(document).scrollTop() > $('.logo_cont').outerHeight() && $('.container').width() > 300) {
            $('.header_menu').addClass('sticky');
        } else {
            $('.header_menu').removeClass('sticky');
        }
    });
	$('.header_menu_left ul li').hover(function(){
		$('.sub-menu:first, .children:first',this).stop(true,true).slideDown('fast');
	},
	function(){
		$('.sub-menu:first, .children:first',this).stop(true,true).slideUp('fast');
	});
    $('#header_menu_id').slicknav();
    var mySwiper = new Swiper ('.swiper-container', {
        pagination: '.swiper-pagination',
        paginationClickable: true,
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        spaceBetween: 30,
		autoplay:5000,
        effect: 'fade'
    })     	
});