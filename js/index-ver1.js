jQuery(document).ready(function($) {
            
            /* 全域變數 */
        	
	        var _winheight = $(window).height();
	        var _bodyheight = $('body').height();
	        var small = 'up';
	        var headerHeight = $('.head').height();
	        var head = headerHeight + $('.intro-video').height();
	        var videoPlay = 'no';
	        var muteChange = 'unmute';

	        /* svg 數字10 動畫 */

	        function transformPolygon() {
			    var myPolygon = d3.select(document.getElementById('finalPath'))
			    var myPolygon2 = d3.select(document.getElementById('finalPath2'))
			    myPolygon
			      .attr('points','4.28,-16.355 -118.719,9.182 -20,1 400,-100')
			      .transition()
			      .attr('points','4.28,-16.355 118.719,9.182 76.681,1 -100,172.024')
			      .transition()
			      .duration(500)
			      .attr('points','4.28,-16.355 118.719,9.182 76.681,197.563 -100,172.024')
			    ,myPolygon2 
			      .attr('points','5.633,-30.878 0,-30.878 5.633,0 5.633,119.695')
			      .transition()
			      .attr('points','5.633,-30.878 93.7375,-10.5885 70.35,109.6545 -17.705,149.212')
			      .transition()
			      .duration(500)
			      .attr('points','5.633,-30.878 187.475,9.701 140.699,219.309 -41.143,178.729')
			}

			function transformPolygonReverse() {
			    var myPolygon = d3.select(document.getElementById('finalPath'))
			    var myPolygon2 = d3.select(document.getElementById('finalPath2'))
			    myPolygon
			      .attr('points','4.28,-16.355 -118.719,9.182 -20,1 400,-100')
			      .transition()
			      .attr('points','4.28,-16.355 118.719,9.182 76.681,1 -100,172.024')
			      .transition()
			      .duration(500)
			      .attr('points','4.28,-16.355 118.719,9.182 76.681,197.563 -100,172.024')
			    ,myPolygon2 
			      .attr('points','5.633,-30.878 0,-30.878 5.633,0 5.633,119.695')
			      .transition()
			      .attr('points','5.633,-30.878 93.7375,-10.5885 70.35,109.6545 -17.705,149.212')
			      .transition()
			      .duration(500)
			      .attr('points','5.633,-30.878 187.475,9.701 140.699,219.309 -41.143,178.729')
			}
	  	

		    function animation() {
				var titleMove = new TimelineMax();
				titleMove.fromTo(".first", 1, {left: -150, opacity: 0}, {left: -130, opacity: 1}).duration( 0.35 );
				titleMove.fromTo(".second", 1, {left: -120, opacity: 0}, {left: -100, opacity: 1}).duration( 0.65 );
				titleMove.fromTo(".third", 1, {left: 20, opacity: 0}, {left: 40, opacity: 1}).duration( 0.95 );
				titleMove.fromTo(".fourth", 1, {left: 100, opacity: 0}, {left: 120, opacity: 1}).duration( 2.05 );
				titleMove.fromTo(".fifth", 1, {left: 130, opacity: 0}, {left: 150, opacity: 1}).duration( 2.35 );
			}
            
            animation();
	        setTimeout(transformPolygon,600)
	        
			$(window).scroll(function(){
            	var opTen = $('#intro-ten').css('opacity');
            	var opset = 'active';

            	if ( opTen < 0.9 ) {
	        		return opset = 'stop';
	        	} else {
	        		return opset = 'active';
	        	}
            	
            	if ( opset == 'stop' ) {
                    animationReverse ();   
	        		transformPolygonReverse();
	        		return opset = 'active';
            	} else {
            		animation ();
	        		transformPolygon();
	        		return opset = 'stop';
            	}
	        	console.log(set)
	        });

	        /* 依照滾輪滾動 */

	        function scrollAni () {
                var introHeight = $('#intro-content').height(); 
                var introOffTop = $('#intro-content').offset().top;
                var introOffBtm = introHeight + introOffTop;
                var introCenter = introOffTop + (introHeight / 2 );
                var _scroll = $(window).scrollTop();
                var offsetBtmCollect = {
	                topic: $('#intro-topic').offset().top + $('#intro-topic').height(),
	                ten: $('#intro-ten').offset().top + $('#intro-ten').height(),
	                copy: $('#intro-copy').offset().top + $('#intro-copy').height(),
	                ep: $('#intro-ep').offset().top + $('#intro-ep').height(),
	                who: $('#cond-who').offset().top + $('#cond-who').height(),
	                age: $('#cond-age').offset().top + $('#cond-age').height(),
	                step: $('#cond-step').offset().top + $('#cond-step').height()
	            }

	            var a = (_scroll - ($('#intro-topic').offset().top/2)) / ( $('#intro-topic').height()) ;
	            var b = (_scroll - ($('#intro-ten').offset().top/2)) / ( $('#intro-ten').height()) ;
	            var c = (_scroll - ($('#intro-copy').offset().top/2)) / ( $('#intro-copy').height()) ;
	            var d = (_scroll - ($('#intro-ep').offset().top/2)) / ( $('#intro-ep').height()) ;
	            var e = (_scroll - ($('#cond-who').offset().top/2)) / ( $('#cond-who').height()) ;
	            var f = (_scroll - ($('#cond-age').offset().top/2)) / ( $('#cond-age').height()) ;
	            var g = (_scroll - ($('#cond-step').offset().top/2)) / ( $('#cond-step').height()) ;
	            
	            $('#intro-topic').css({'opacity':a});
	            $('#intro-ten').css({'opacity':b});
	            $('#intro-copy').css({'opacity':c});
	            $('#intro-ep').css({'opacity':d});
	            $('#cond-who').css({'opacity':e});
	            $('#cond-age').css({'opacity':f});
	            $('#cond-step').css({'opacity':g});

	            var leftLimit = g * 75 - 200;
	           
                if ( g > 1 ) {
                	$('.step-1').css({'left':'0','opacity':g});
           			$('.step-2').css({'left':'0','opacity':g});
            		$('.step-3').css({'left':'0','opacity':g});
                } else if ( g < 1 ){
                	$('.step-1').css({'left':'-=100','opacity':g});
           			$('.step-2').css({'left':'-=100','opacity':g});
            		$('.step-3').css({'left':'-=100','opacity':g});
                }
	            

                var moveParam = (_scroll - introCenter /2)  / introCenter * -1;
                var leftOpaParam = (_scroll + ($('.intro-animateLeft').offset().top))*0.2 / ( $('.intro-animateLeft').height());
                var rightOpaParam = (_scroll + ($('.intro-animateRight').offset().top))*0.2 / ( $('.intro-animateRight').height());
                var leftMove = introOffTop ;

               
                if ( _scroll < leftMove ) {
                	
                	var leftMoveIn = ( moveParam * introOffTop * -2 - $('.intro-animateLeft').height()/2)
                	var rightMoveIn = ( moveParam * introOffTop * -2 - $('.intro-animateRight').height()/2)
                    $('.intro-animateLeft').css({'left':leftMoveIn,'opacity': leftOpaParam });
                    $('.intro-animateRight').css({'right':rightMoveIn, 'opacity': rightOpaParam });

                } else if ( _scroll > introOffTop + 450  ) {
                    
                    /*var leftMoveOut =  moveParam * introOffTop * 2 + $('.intro-animateLeft').height()/2;
                    var rightMoveOut = ( moveParam * introOffTop * 2 + $('.intro-animateRight').height()/2)
                    $('.intro-animateLeft').css('left', -195 + leftMoveOut*0.1);
                    $('.intro-animateRight').css({'right': -158 + rightMoveOut*0.1});*/
                    
                } else {
                	$('.intro-animateLeft').css({'left':'-195px','opacity':'1'});
                	$('.intro-animateRight').css({'right':'-158px','opacity':'1'});

                }


	        }
            
            scrollAni();
	        $(window).scroll(function(){
     
	        	scrollAni();

	        });
	        

            

	        /* 選單滾動 */
	    

	        $('.nav-unit_1').click(function(event) {
	        	var introTop = $('#intro-content').offset().top;
                var scrollCorrect = introTop - headerHeight;
	        	$('html, body').animate({
				    scrollTop: introTop-100,
				    scrollLeft: 0
				}, 1000);
	        });

        	/* 選單動畫 */

            function navin(e) {
                var _thisTop = e.siblings('.top');
				var _thisBtm = e.siblings('.bottom');
			    var tllg = new TimelineMax();
				tllg.fromTo(_thisTop, 1, {top:0, left:0}, {top:-15, left:27}).fromTo(_thisBtm, 1, {bottom:0, right:0}, {bottom:-15, right:27}).duration(  0.17 );
            }
            function navout(e) {
            	var _thisTop = e.siblings('.top');
				var _thisBtm = e.siblings('.bottom');
			    var tllg = new TimelineMax();
				tllg.fromTo(_thisTop, 1, {top:-15, left:27}, {top:0, left:0}).fromTo(_thisBtm, 1, {bottom:-15, right:27},  {bottom:0, right:0}).duration( 0.17 );
            }


		    $(".basic-text").mouseover(function() {
				navin($(this));
			});
			$(".basic-text").mouseleave(function() {
				navout($(this));
			});

	        /* 手機頁面點擊選單 */
              
	        $('.mb-login').click(function () {
				$('.mb-nav').toggleClass('dpn');
				$(this).toggleClass('close');
	            $('body').toggleClass('hid');
	            $('.header').toggleClass('mb-logopen');
	            $('.header-btn').toggleClass('active');
			});

			$('.mb-nav').click(function () {
				$('.mb-login').toggleClass('dpn');
				$(this).toggleClass('close');
	            $('body').toggleClass('hid');
	            $('.header').toggleClass('mb-navopen');
	            $('.nav').toggleClass('active');
			});

			

			$('.nav-unit, .btn-group').click(function () {
				$('.mb-login, .mb-nav').removeClass('close').removeClass('dpn');
	            $('body').removeClass('hid');
	            $('.header').removeClass('mb-navopen').removeClass('mb-logopen');
	            $('.nav').removeClass('active');
	            $('.header-btn').removeClass('active');
	        });

	        /* 淡入 */

	        /*function fadein(e) {
	        	
	        	var _scroll = $(window).scrollTop();
	            var domIntro = $('.intro-content').offset().top;
	            var _domIntro = domIntro - head;


	            
	          
	            if (_scroll > _domIntro && _scroll < _domIntro*3 ) {
	            	$('.intro-animateLeft').removeClass('fadeOutLeft').addClass('fadeInLeft');
	            	$('.intro-animateRight').removeClass('fadeOutRight').addClass('fadeInRight');
	            } else if ( _scroll > _domIntro*3 ) {
	            	$('.intro-animateLeft').addClass('fadeOutLeft');
	            	$('.intro-animateRight').addClass('fadeOutRight');
	            } else {
	            	$('.intro-animateLeft').addClass('fadeOutLeft');
	            	$('.intro-animateRight').addClass('fadeOutRight');
	            }
	        }

	        function fadeInStep () {
	        	var bottom_of_object = $('#intro-condition').offset().top + $('#intro-condition').outerHeight();
            	var bottom_of_window = $(window).scrollTop() + $(window).height();
            	if( bottom_of_window > bottom_of_object ){
	                $('.step-1').animate({'opacity':'1'},1000);
	                $('.step-2').animate({'opacity':'1'},1500);
	                $('.step-3').animate({'opacity':'1'});
	            }
	          
	        }*/
	        /*function fadeInNormal (x,y) {
	        	var bottom_of_object = x.offset().top + x.outerHeight();
            	var bottom_of_window = $(window).scrollTop() + $(window).height();
            	if( bottom_of_window > bottom_of_object ){
	                y.animate({'opacity':'1'},1000);
	            }
	          
	        }*/


	        /* 視差 */

	        function parallax (){
	        	var _winheight = $(window).height();
	        	var _bodyheight = $('body').height();
	        	var _scroll = $(window).scrollTop();
				var _scrollEnd = _bodyheight - _winheight;
				var _rate = _scroll / _scrollEnd;
				var multiscroll = _scrollEnd - (_scrollEnd*_rate);
				$('.th-link').css('background-position','50%'+ ((_rate*100))+'%');
	        	$('.link-unit').css('transform','translateY('+(multiscroll)+'px) skewX(-7deg)');
	            $('.link-title').css('transform','translateY('+(multiscroll)+'px) skewX(-7deg)');
	            $('.th-title').css({'transform':'translate3d(0,'+(-0.8*_scroll)+'px,0','background-position':'50%'+ ((_rate*100))+'%'});
	        }

	        /* 閃動 */

	        function shine () {
	            var domIntro = $('.intro-condition').offset().top; 
	            var _domIntro = domIntro - head;
                
                /*$('.cond-title')*/
	        } 

	        /* 滾動縮短高度 */

	        function shirnkJudge () {

	        	var _scroll = $(window).scrollTop();
	        	if ( _scroll < 292) {
	        		return small = 'up';
	        	} else {
	        		return small = 'down';
	        	}
	        }

	        function shrink () {
                if ( small == 'down' ) {
                	$('.header').addClass('scrolldown');
                    
                } else if (small == 'up'){
                	$('.header').removeClass('scrolldown');
                	
                }
	        }

	        /* 下方視覺淡出 */

	        function videoFadeIn () {
	        	$('.video-cover').fadeIn('fast');
	        	$('.circle').css({'opacity':'1','z-index':'5'});
	        	$('#controls').css('z-index','-1');
	        	return videoPlay = 'clickIn';
	        }
            
            function videoFadeOut () {
            	var video = $('#video-block');
            	$('.video-cover').fadeOut("fast", function(){
            		setTimeout(videoFadeIn,16000);
            	});
            	$('#controls').css('z-index','2');
            	video[0].load();
    			video[0].play();
    			return videoPlay = 'yes';
            } 

            /* 影像滑鼠滑動顯示 */

            $('.circle').click(function(){
                videoFadeOut();
                return videoPlay = 'yes';
            });        

            /* 影片位置調整 */

            function videoPosition () {
	        	var winWidth = $(window).width();
			  	var videoWidth = $('#video-block').width();
			  	var videoHeight = $('.intro-video').height();
			  	var center = videoWidth - winWidth;
			  	var halfCenter = center / 2;
                $('#video-block').css('height',videoHeight);
			  	if (center > 0) {
		            $('#video-block').css('left',halfCenter*(-1));
			  	} else if (center == 0) {
			  		$('#video-block').css('left',0);
			  	} else if (center < 0) {
			  		$('#video-block').attr({'height':videoHeight});
			  	}
	        }	

	        /* 影片靜音切換 */


	        function mute () {
	        	if ( muteChange == 'unmute' ) {
	         	 	$("#video-block").prop('muted', true); //mute
	         	 	return muteChange = 'mute';
	         	} else if ( muteChange == 'mute' ) {
	         	 	$("#video-block").prop('muted', false); //unmute
	         	 	return muteChange = 'unmute';
	         	}
	         	 
	        }

	        $('#mute').click(function(){
	         	mute ();
	         	$(this).toggleClass('on');     	 
	        });  

            /* 執行 */
            
            shirnkJudge();

	        /*fadein();*/
	        shrink();
	        videoPosition();
	       /* fadeInNormal($('#intro-topic'), $('#intro-topic'));
        	fadeInNormal($('#intro-ten'), $('#intro-ten'));
        	fadeInNormal($('#intro-copy'), $('#intro-copy'));
        	fadeInNormal($('#intro-ep'), $('#intro-ep'));
        	fadeInNormal($('#cond-who'), $('#cond-who'));
        	fadeInNormal($('#cond-age'), $('#cond-age'));
        	fadeInNormal($('#cond-step'), $('#cond-step'));*/


	        $(window).scroll(function(){
	        	
	        	/*fadein();*/
	        	parallax();
	        	shirnkJudge();
	        	shrink();

	        	/*fadeInNormal($('#intro-topic'), $('#intro-topic'));
	        	fadeInNormal($('#intro-ten'), $('#intro-ten'));
	        	fadeInNormal($('#intro-copy'), $('#intro-copy'));
	        	fadeInNormal($('#intro-ep'), $('#intro-ep'));
	        	fadeInNormal($('#cond-who'), $('#cond-who'));
	        	fadeInNormal($('#cond-age'), $('#cond-age'));
	        	fadeInNormal($('#cond-step'), $('#cond-step'));*/
	            /*fadeInStep(); 	*/
	        	if ( videoPlay == 'no') {
	        		videoFadeOut();
	        	} 
	        	

	        });
            
        	setTimeout(function(){
        		if ( videoPlay == 'no') {
                    videoFadeOut();
        		}
        	},3000);
            

	        $(window).resize(function(event) {
	        	videoPosition();
	        	parallax ();
	        }); 
            
            
        
        });