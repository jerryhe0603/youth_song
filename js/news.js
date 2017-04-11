
jQuery(document).ready(function($) {

	        

        	function animation(e) {
                var _thisTop = e.siblings('.top');
				var _thisBtm = e.siblings('.bottom');
			    var tllg = new TimelineMax();
				tllg.fromTo(_thisTop, 1, {top:0, left:0}, {top:-15, left:27}).duration( 0.15 );
				tllg.fromTo(_thisBtm, 1, {bottom:0, right:0}, {bottom:-15, right:27}).duration( 0.15 );
            }
            function animation2(e) {
            	var _thisTop = e.siblings('.top');
				var _thisBtm = e.siblings('.bottom');
			    var tllg = new TimelineMax();
				tllg.fromTo(_thisTop, 1, {top:-15, left:27}, {top:0, left:0}).duration( 0.15 );
				tllg.fromTo(_thisBtm, 1, {bottom:-15, right:27},  {bottom:0, right:0}).duration( 0.15 );
            }

		    $(".basic-text").mouseover(function() {
				animation($(this));
			});
			$(".basic-text").mouseleave(function() {
				animation2($(this));
			});

			$('.news-useful').click(function () {
	            $('.popout').addClass('active');
	            $('.popbg').addClass('active');
	            $('body').addClass('hid');
			})
			
			$('.pop-exit').click(function () {
	            $('.popout').removeClass('active');
	            $('.popbg').removeClass('active');
	            $('body').removeClass('hid');
			})

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




	    });