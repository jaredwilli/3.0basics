<?php get_header(); ?>
</head>
<body class="<?php base_body_class(); ?>">
<?php include(TEMPLATEPATH . '/branding.php'); ?>

<div id="content" class="inner clearfix">
	<div id="content-main">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	<div class="post" id="post-<?php the_ID(); ?>">
		<h1 class="post-title"><?php the_title(); ?></h1>
		
		<ul class="postinfo clearfix">
			<li class="authordata"><?php the_time('M j, Y'); ?> by <strong><?php the_author(); ?></strong></li>
			<li class="commentdata"><?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?></li>
		</ul>
		
		<div class="entry clearfix">
			<?php the_content(__('Read more'));?>
		</div><!--// end #entry -->
	</div><!--// end #post-<?php the_ID(); ?> -->
	
	<?php comments_template(); ?>

	<?php endwhile; ?>
		<div class="navigation clearfix">
			<div class="alignleft"><?php next_posts_link('&laquo; Previous Entries'); ?></div>
			<div class="alignright"><?php previous_posts_link('Next Entries &raquo;'); ?></div>
		</div>
	<?php else :
		// Show 404 message
		if (function_exists('bb_404')) { bb_404(); }
	endif; ?>

	</div><!-- end #content-main -->
	<div id="content-sub">
		<?php get_sidebar(); ?>
	</div> <!-- end #content-sub -->
</div><!-- end #content -->
<?php get_footer(); ?>