$(document).ready(function(){
	//-------------------------------------popout---------------------------------------------------//
	//$(document).on('click', '.submit_1', function(event){
	// $('#utube_popup').fadeIn(150);
	// $('#utube_popup_b').fadeTo(150,0.75);
	// var screen_width = $(window).width();
	// var screen_height = $(window).height();	
	// var adj_screen_width = (screen_width - 500)/2;
	// $('.utube_back').css({'top':'210px','left':adj_screen_width});
	// $(window).resize(function() {
	// 		var screen_width = $(window).width();
	//		var screen_height = $(window).height();	
	//		var adj_screen_width = (screen_width - 500)/2;
	//		$('.utube_back').css({'top':'210px','left':adj_screen_width});
	// });
	//});

	$(document).on('click', '.youtube_popup_close', function(event){
		$('#utube_popup').fadeOut(150);
		$('#utube_popup_b').fadeOut(150);
		$('.utube_popup_play').html('');
		$('.utube_download').attr('href','');
		$('.utube_info').attr('href','');
		$('.utube_other').attr('href','');
	});
	//-------------------------------------popout---------------------------------------------------//

	$('.allrecord_r_linkbtn a').hover(function(){$(this).find('img').stop().fadeTo(150,0.8)},function(){$(this).find('img').stop().fadeTo(80,1)});
	$('.imgupload').hover(function(){$(this).find('.imgupload_hover').stop().fadeTo(120,0.75)},function(){$(this).find('.imgupload_hover').stop().fadeTo(120,0)});
	$('.form_imgback').hover(function(){$(this).find('.img_delete').stop().fadeTo(120,0.75)},function(){$(this).find('.img_delete').stop().fadeTo(120,0)});
	$('.imgupload_hover').click(function(){
		$(this).parent().find('.uploadme').click();
	});

	$('.uploadarea_btn2').click(function(){
		$(this).parent().find('.uploadme2').click();
	});
	
	/*if (!$.browser.opera) {
		$('select.select').each(function(){
			var title = $(this).attr('title');
			if( $('option:selected', this).val() != ''  ) title = $('option:selected',this).text();
			$(this).css({'z-index':10,'opacity':0,'-khtml-appearance':'none'}).after('<span class="select">' + title + '</span>')
				.change(function(){
					val = $('option:selected',this).text();
					$(this).next().text(val);
				})
		});
	};*/
	$('input:radio').change(
		function(){
			$('input[name=ta_deal]:checked').val() == 1 ? $('#contract_input').show(): $('#contract_input').hide();
		}
	);
	$('input:checkbox').change(
		function(){
			$(this).attr('checked') ? $(this).parents('div:eq(2)').addClass('form_checked'):$(this).parents('div:eq(2)').removeClass('form_checked');
			$(this).attr('checked') ? $(this).parents('div:eq(2)').find('.check_readonly').removeAttr('readonly').css('background','#fff'):$(this).parents('div:eq(2)').find('.check_readonly').attr('readonly','true').css('background','none');
		}
	);
	
	/*
	$('input:radio').change(
		function(){
			$(this).attr('checked') ? $(this).parents('div:eq(2)').addClass('form_checked'):$(this).parents('div:eq(2)').removeClass('form_checked');
		}
	);*/
	
});

//------------------------------------------------------------------------------

function DrawImage2(ImgD,FitWidth,FitHeight){
		var image=new Image();
		image.src=ImgD.src;
   		if(image.width>0 && image.height>0){
        
		if(image.width/image.height>= FitWidth/FitHeight){ //寬圖用設定
            ImgD.width=FitWidth;
			
			var adjust = 0 - ((ImgD.height - FitHeight) / 2);
			var adjust2 = 0 - ((ImgD.width - FitWidth) / 2);
			$(ImgD).parent('div').css({'top':adjust,'left':adjust2});
        }
		else if(image.height>FitHeight){	//高圖用設定
			ImgD.width=FitWidth;
			
			var adjust = (FitHeight-(FitWidth*ImgD.height/ImgD.width))/2;
			
			$(ImgD).parent('div').css({'top':Math.floor(adjust)});
		}
		else if(image.height<FitHeight){	//小圖用設定
			ImgD.width=FitWidth;
		}
		else{	//方形圖用設定
			ImgD.width=FitWidth;
		}
		
    }
}