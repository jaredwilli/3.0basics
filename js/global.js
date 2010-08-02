$(function(){

	$("#menu-header ul.menu ul").css({ display: 'none' });
	$("#menu-header ul.menu li").hover(function() {
		$(this).find('ul.sub-menu')
			.stop(true, true).delay(50).animate({ "height": "show", "opacity": "show" }, 200 );
	}, function(){
		$(this).find('ul.sub-menu')
			.stop(true, true).delay(50).animate({ "height": "hide", "opacity": "hide" }, 200 );
	});

	/* Fade in - Fade out */
	$("a img, a").css("opacity","1");
	$("li img, li a").siblings().hover(function () {
		$(this).stop().animate({ opacity: 0.75 }, 500);
	}, function () { 
		$(this).stop().animate({ opacity: 1 }, 500);
	});

	/* Bobble Upwards on Hover */
	$('a').hover(function() {
		$(this).children('img').stop().animate({ "top" : "-10px"}, 200);
	}, function() {
		$(this).children('img').stop().animate({ "top" : "0px"}, 200);
		
	});

	/* Nudge Links on Click	*/
    $("#content a").click(function(){
    	$(this).stop().animate({ marginTop: "1px" }, 100); 
	}, function() {
		$(this).stop().animate({ marginTop: "0px" }, 100);
	});

	//Comments hint box
	$('#commentsform #rules-toggle').show(0);
	$('#commentsform .comment-rules').hide(0);
	$('#commentsform #rules-toggle a').toggle(
		function(){
			$('#commentsform #rules-toggle a').html("hide" );
			$('#commentsform .comment-rules').stop(true,true).slideDown(140);
			return false;
		},
		function(){
			$('#commentsform #rules-toggle a').html("show allowed tags" );
			$('#commentsform .comment-rules').stop(true,true).slideUp(130);
			return false;
		}
	);

});