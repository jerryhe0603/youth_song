var _winheight = $(window).height();
var _winWidth = $(window).width();
var _bodyheight = $('body').height();
var small = 'up';
var headerHeight = $('.head').height();
var videoPlay = 'no';
var muteChange = 'unmute';
var opset = 'active';


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


