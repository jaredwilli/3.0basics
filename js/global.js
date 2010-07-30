$(function(){

	$("#menu-header ul.menu ul").css({ display: 'none' });
	$("#menu-header ul.menu li").hover(function() {
		$(this).find('ul.sub-menu')
			.stop(true, true).delay(50).animate({ "height": "show", "opacity": "show" }, 200 );
	}, function(){
		$(this).find('ul.sub-menu')
			.stop(true, true).delay(50).animate({ "height": "hide", "opacity": "hide" }, 200 );
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