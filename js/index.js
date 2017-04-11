jQuery(document).ready(function($) {
            
            /* 全域變數 */
        	
	        var _winheight = $(window).height();
	        var _winWidth = $(window).width();
	        var _bodyheight = $('body').height();
	        var small = 'up';
	        var headerHeight = $('.head').height();
	        var head = headerHeight + $('.intro-video').height();
	        var videoPlay = 'no';
	        var muteChange = 'unmute';
	        var opset = 'active';

	        /* 回到最頂層 */

	        $('#scrollUp').click(function(){
	        	var $body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');
				$body.animate({
					scrollTop: 0
				}, 1000);
		 
				return false;
	        })

	        /* svg 數字10 動畫 */


			    function animation () {
					var titleMove = new TimelineMax();
					titleMove.fromTo(".first", 0.5, {left: -290, opacity: 0}, {left: -270, opacity: 1});
					titleMove.fromTo(".second", 0.5, {left: -210, opacity: 0}, {left: -190, opacity: 1});
					titleMove.fromTo(".third", 0.5, {left: 10, opacity: 0}, {left: 30, opacity: 1});
					titleMove.fromTo(".fourth", 0.5, {left: 190, opacity: 0}, {left: 210, opacity: 1});
					titleMove.fromTo(".fifth", 0.5, {left: 270, opacity: 0}, {left: 290, opacity: 1});
					return opset = 'stop';
				}

				function animationReverse () {
					var titleMove = new TimelineMax();
					titleMove.fromTo(".first", 0.05, {left: -130, opacity: 1}, {left: -150, opacity: 0 });
					titleMove.fromTo(".second", 0.05, {left: -100, opacity: 1}, {left: -120, opacity: 0 });
					titleMove.fromTo(".third", 0.05, {left: 40, opacity: 1}, {left: 20, opacity: 0 });
					titleMove.fromTo(".fourth", 0.05, {left: 120, opacity: 1}, {left: 100, opacity: 0 });
					titleMove.fromTo(".fifth", 0.05, {left: 150, opacity: 1}, {left: 130, opacity: 0 });
					return opset = 'active';
				}

				function animationMB () {
					var titleMove = new TimelineMax();
					var outWidth = $('.motive-title').width();

					titleMove.fromTo(".first", 0.5, {left: (outWidth*0.42*-1), opacity: 0}, {left: (outWidth*0.29*-1), opacity: 1});
					titleMove.fromTo(".second", 0.5, {left: (outWidth*0.32*-1), opacity: 0}, {left: (outWidth*0.18*-1), opacity: 1});
					titleMove.fromTo(".third", 0.5, {left: (outWidth*0.11*-1), opacity: 0}, {left: (outWidth*0.04), opacity: 1});
					titleMove.fromTo(".fourth", 0.5, {left: (outWidth*0.05), opacity: 0}, {left: (outWidth*0.20), opacity: 1});
					titleMove.fromTo(".fifth", 0.5, {left: (outWidth*0.16), opacity: 0}, {left: (outWidth*0.30), opacity: 1});
					return opset = 'stop';
				}

				function animationReverseMB () {
					var titleMove = new TimelineMax();
					titleMove.fromTo(".first", 0.05, {left: 0, opacity: 1}, {left: -20, opacity: 0 });
					titleMove.fromTo(".second", 0.05, {left: 0, opacity: 1}, {left: -20, opacity: 0 });
					titleMove.fromTo(".third", 0.05, {left: 0, opacity: 1}, {left: -20, opacity: 0 });
					titleMove.fromTo(".fourth", 0.05, {left: 0, opacity: 1}, {left: -20, opacity: 0 });
					titleMove.fromTo(".fifth", 0.05, {left: 0, opacity: 1}, {left: -20, opacity: 0 });
					return opset = 'active';
				}
                


              
	        	
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
		                /*age: $('#cond-age').offset().top + $('#cond-age').height(),*/
		                step: $('#cond-step').offset().top + $('#cond-step').height()
		            }

		            var a = (_scroll - ($('#intro-topic').offset().top/3)) / ( $('#intro-topic').height()) ;
		            var b = (_scroll - ($('#intro-ten').offset().top/3)) / ( $('#intro-ten').height()) ;
		            var c = (_scroll - ($('#intro-copy').offset().top/3)) / ( $('#intro-copy').height()) ;
		            var d = (_scroll - ($('#intro-ep').offset().top/3)) / ( $('#intro-ep').height()) ;
		            var e = (_scroll - ($('#cond-who').offset().top/3)) / ( $('#cond-who').height()) ;
		            var f = (_scroll - ($('#cond-step').offset().top/3)) / ( $('#cond-step').height()) ;
		            /*var g = (_scroll - ($('#cond-age').offset().top/3)) / ( $('#cond-age').height()) ;*/
	                
	                var maxTopArray = new Array((-8+a*8),(-8+b*8),(-8+c*8),(-8+d*8),(-8+e*8),(-8+f*8));
		          
	                for (var i = 0; i <= 7; i++ ) {
	                	if ( maxTopArray[i] > 0 ) {
			            	maxTopArray[i] = 0;
			            }
	                }
		            
		           
		            $('#intro-topic').css({'opacity':a+0.5});
		            $('#intro-topic span').css({'opacity':a,'top':maxTopArray[0]});
		            $('#intro-ten').css({'top':maxTopArray[1]});
		            $('#intro-copy').css({'opacity':c,'top':maxTopArray[2]});
		            $('#intro-copy span').css({'opacity':c,'top':maxTopArray[2]});
		            $('#intro-ep').css({'opacity':d,'top':maxTopArray[3]});
		            $('#intro-condition').css({'opacity':e,'top':maxTopArray[4]});
                    
                    var opLast = $('.fifth').css('opacity');
		            
		            

		            if ( b >= 0.5 ) {
		            	
	            		$('#scrollUp').css('visibility','visible');
	            		$('#intro-ten').css({'opacity':1});
	            		var winWidth = $(window).width();

	            		if ( winWidth > 1024 ) {
		            		if (opset == 'active'){
	            				while (opset == 'active') {
						    		animation();
						    		return opset = 'stop';
						        };
	            			}
						};
						
		            } else if ( b < 0.5) {
		            	$('#scrollUp').css('visibility','hidden');
		            	$('#intro-ten').css({'opacity':0});
		            	return opset = 'active';
		            } 



		            if ( e >= 1 ) {
		            	$('.cond-unit h4, .cond-unit p, .cond-unit ul').css({'animation-name':'fallDown','opacity':'1','animation-duration':'1s'});
		            	$('#intro-condition').addClass('active');
		            } else {
		            	$('.cond-unit h4, .cond-unit p, .cond-unit ul').css({'animation-name':'riseUp','opacity':'0','animation-duration':'.1s'});
		            	$('#intro-condition').removeClass('active');
		            }
                     
		
	                
	                if ( f >= 1 ) {
	                	$('.step-1 .wt-cover').css({'width':'-=250','opacity':0});
	           			$('.step-2 .wt-cover').css({'width':'-=250','opacity':0});
	            		$('.step-3 .wt-cover').css({'width':'-=250','opacity':0});
	                } else {
	                	$('.step-1 .wt-cover').css({'width':'100%','opacity':1});
           				$('.step-2 .wt-cover').css({'width':'100%','opacity':1});
            			$('.step-3 .wt-cover').css({'width':'100%','opacity':1});
	                }
		           
		           /* $('#cond-who .cond-title, #cond-who p, #cond-age .cond-title, #cond-age p, #cond-step .cond-title').css({'opacity':0,'top':'-20px'});
                     
		            if ( e >= 1 ) {
		            	$('#cond-who .cond-title').css({'opacity':e,'top':maxTopArray[4]});
		            	$('#cond-who p').css({'opacity':e,'top':maxTopArray[4]});
		            	if (f >= 1) {
			            	$('#cond-age .cond-title').css({'opacity':f,'top':maxTopArray[5]});
			            	$('#cond-age p').css({'opacity':f,'top':maxTopArray[5]});
			            } if ( g >= 1 ) {
			            	$('#cond-step .cond-title').css({'opacity':g,'top':maxTopArray[6]});
			            }

	                 else if ( g < 1 ){
	                	
	                }
		            
		            } 
	                */

		           

	                var moveParam = (_scroll - introCenter)  / introCenter * -1;
	                var leftOpaParam = (_scroll + ($('.intro-animateLeft').offset().top))*0.2 / ( $('.intro-animateLeft').height());
	                var rightOpaParam = (_scroll + ($('.intro-animateRight').offset().top))*0.2 / ( $('.intro-animateRight').height());
	                var leftMove = introOffTop ;

	               
	                if ( _scroll < leftMove + introCenter/2 ) {
	                	var leftMoveIn = ( moveParam * introOffTop * -0.8  - $('.intro-animateLeft').height()/3.5)
	                	var rightMoveIn = ( moveParam * introOffTop * -0.6 - $('.intro-animateRight').height()/2.3)
	                	
	                    $('.intro-animateLeft').css({'left':leftMoveIn,'opacity': leftOpaParam });
	                    $('.intro-animateRight').css({'right':rightMoveIn, 'opacity': rightOpaParam });

	                } else {
                        
	                	$('.intro-animateLeft').css({'left':'-215px','opacity':'1'});
	                	$('.intro-animateRight').css({'right':'-250px','opacity':'1'});

	                }


		        }
	            
	           if (_winWidth > 1024) {
                    scrollAni();
	            }
	            
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
		        

		        if ( _winWidth < 1024 ) {
                    $('.cond-unit h4, .cond-unit p, .cond-unit ul').css({'animation-name':'fallDown','opacity':'1','animation-duration':'1s'});
		            $('#intro-condition').addClass('active');
		            $('.step-1 .wt-cover').css({'width':'-=250','opacity':0});
           			$('.step-2 .wt-cover').css({'width':'-=250','opacity':0});
            		$('.step-3 .wt-cover').css({'width':'-=250','opacity':0});
            		
					while (opset == 'active') {
			    		animationMB();
			    		return opset = 'stop';
			    	};
			    				    	
		        };


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
		         	$(this).toggleClass('sound-mute');     	 
		        });  


            /* 執行 */
            
	            shirnkJudge();
		        shrink();
		        videoPosition();
		        /*fadein();*/
		       	/*fadeInNormal($('#intro-topic'), $('#intro-topic'));
	        	fadeInNormal($('#intro-ten'), $('#intro-ten'));
	        	fadeInNormal($('#intro-copy'), $('#intro-copy'));
	        	fadeInNormal($('#intro-ep'), $('#intro-ep'));
	        	fadeInNormal($('#cond-who'), $('#cond-who'));
	        	fadeInNormal($('#cond-age'), $('#cond-age'));
	        	fadeInNormal($('#cond-step'), $('#cond-step'));*/


		        $(window).scroll(function(){
		        	var winWidth = $(window).width();
		        	if (winWidth >= 1024) {
                        parallax();
                        shirnkJudge();
		        		shrink();
		        	}

		        	
		        	

		        	if ( videoPlay == 'no') {
		        		videoFadeOut();
		        	} 
	                /*fadein();*/
		        	/*fadeInNormal($('#intro-topic'), $('#intro-topic'));
		        	fadeInNormal($('#intro-ten'), $('#intro-ten'));
		        	fadeInNormal($('#intro-copy'), $('#intro-copy'));
		        	fadeInNormal($('#intro-ep'), $('#intro-ep'));
		        	fadeInNormal($('#cond-who'), $('#cond-who'));
		        	fadeInNormal($('#cond-age'), $('#cond-age'));
		        	fadeInNormal($('#cond-step'), $('#cond-step'));*/
		            /*fadeInStep(); 	*/
		        });
	            
	        	setTimeout(function(){
	        		if ( videoPlay == 'no') {
	                    videoFadeOut();
	        		}
	        	},3000);
	            

		        $(window).resize(function(event) {
		        	videoPosition();
		        	parallax ();
		        	scrollAni();
		        }); 
            
            
        
        });