$(document).ready(function(){

			//header fixed 
			var totalheight = $('.gallery_wall').height();
			
			
			$(window).scroll(function(){
				($(window).scrollTop() > totalheight) ? $('header').css({'position':'fixed','top':0}) : $('header').css({'position':'absolute','top':totalheight});
			});
			
});

