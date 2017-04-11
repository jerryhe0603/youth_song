$(document).ready(function(){
    
    /* 最新消息點擊閱讀更多後動作 */

	$('.un-news-more').click(function(e){
		var a = $(this).parents('.cover-news_unit').attr('data-number');
		$('#panel-'+a).addClass('is-opa-full--active');
        $('.content').addClass('din');
    });

    /* 滾動至目標錨點 */
    
    $('#stepBtn').click(function(){
        $('.content').scrollTo($('#step'),800);
    });
    
    /* 點擊關閉彈出視窗 */
    
    $('.news-panel_exit').click(function(){
        $('.is-opa-full').removeClass('is-opa-full--active');
        $('.content').removeClass('flow');
    });

    /* 側選單開啟 */
    
    $('.off-open_btn').click(function(){
        $('.m-off-horiz').toggleClass('is-off-active');
        $(this).toggleClass('is-off-active');
    });

    /* 側選單關閉 */

    $('.un-hd_link').click(function(){
    	$('.m-off-horiz').removeClass('is-off-active');
    	$('.off-open_btn').removeClass('is-off-active');
    });
    
    /* 最新消息基本設定 */

    $(function() {
      $('#news').slidesjs({
        width: 320,
        height: 520,
        navigation: false,
        pagination: false

      });
    });

    /* 投票視窗跳出 */
    
    $('.list-login,.btn-login').off().on('click',function(){
        $('#vote-popout-bg').animate({width:'100%'},600,function(){
            $('#vote-login').fadeIn(400);
        });
    });    

    

    $('.btn-close').on('click',function(){
        $(this).parents('.th-vote-popout').fadeOut(400,function(){
            $('#vote-popout-bg').animate({width:'0'},1000);
            
        });
        $('.popout-copylink').empty();
    });

    
    $('#btn-sign').on('click',function(){
        $('#vote-login').fadeOut(400,function(){
            $('#vote-sign').fadeIn(400);
        });
    });
    $('#btn-recode').on('click',function(){
        $('#vote-login').fadeOut(400,function(){
            $('#vote-recode').fadeIn(400);
        });
    });
    $('#btn-remail').on('click',function(){
        $('#vote-login').fadeOut(400,function(){
            $('#vote-remail').fadeIn(400);
        });
    });


    var TimeFn = null;
    
    $('.list-share').on('click',function(){

        var _link = $(this).attr('data-url');
        $('.popout-copylink').append(_link);
        $('#vote-popout-bg').animate({width:'100%'},600,function(){
            $('#vote-sharecopy').fadeIn(400);
        });
        $('#vote-sharecopy').children('#share_url').attr('value',_link);

    });    

    
});