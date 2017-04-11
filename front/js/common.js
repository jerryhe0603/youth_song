$(document).ready(function(){
			
			//hover_self function for hover effect by DK
			$('.col_hover').hover(function(){$(this).find('img').fadeTo(60,0.8)},function(){$(this).find('img').fadeTo(60,1);});
			$('.hover70').hover(function(){$(this).fadeTo(110,0.7);},function(){$(this).fadeTo(60,1);});
			$('.hover80').hover(function(){$(this).fadeTo(110,0.8);},function(){$(this).fadeTo(60,1);});
			
			//index gallery_wall fadeout effect 
			setTimeout(function(){$('.gallery_wall_cover').fadeOut(700)},2600);
			$('.gallery_wall_cover').hover(function(){$(this).delay(300).fadeOut(700)/*.find('.visual').animate({top:-500},300,'easeInCirc')*/;});
			
			//remove the margin 
			$('.calc .col_item_s').each(function(i){
										   if(i % 4 == 3){$(this).css({'marginRight':'0'})}; 
										   });
			$('.feelike .col_item_s').each(function(i){
										   if(i % 3 == 2){$(this).css({'marginRight':'0'})}; 
										   });
			$('.calc2 .col_item_s').each(function(i){
										   if(i % 4 == 3){$(this).css({'marginRight':'0'})}; 
									   });
			
			//add fav
			$('.col_item_s').hover(function(){$(this).find('.addfav').stop().fadeTo(100,1);$(this).find('.classify_cover').stop().hide();},function(){$(this).find('.addfav').stop().delay(300).fadeTo(160,0);$(this).find('.classify_cover').stop().show();});
			$(document).on('click', '.addfav',
							function(){
								$(this).addClass('btn_on');
								$(this).find('.text').html('已加入');
							});
			$(document).on('click', '.btn_on',
							function(){$(this).removeClass('btn_on');
							$(this).find('.text').html('我的最愛');
							});
			
			
			
			//hover logout_btn
			$('.logout_btn').hover(function(){$(this).find('.user_setting_menu').stop().slideDown(70);},function(){$(this).find('.user_setting_menu').stop().slideUp(20);});
			
			
			
});

