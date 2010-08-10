<?php get_header(); ?>
<?php get_template_part( 'branding' ); ?>

<div id="content" class="inner clearfix">
	<div id="content-main" role="main">

		<div <?php post_class('clearfix'); ?> id="post-<?php the_ID(); ?>">
			<h1 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
			<ul class="postinfo clearfix">
				<li class="authordata"><?php the_time('M j, Y'); ?><!-- at <?php the_time(); ?>--> by <strong><?php the_author(); ?></strong></li>
				<li class="commentdata"><?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?></li>
			</ul>
			<div class="entry clearfix">
				<?php the_content(__('Read more')); ?>
			</div><!--// end #entry -->
				<div class="fl post-tags"><?php the_tags( __( ' ' ), ' , ', ' ' ); ?></div>
				<div class="fr edit-link"><?php edit_post_link( __( 'Edit' ) ); ?></div>
		</div><!--// end #post-<?php the_ID(); ?> -->

	<?php endwhile; ?>

		<?php if( function_exists( 'wp_pagenavi' )) { wp_pagenavi(); } else { ?>
		<div class="navigation clearfix">
			<div class="alignleft"><?php next_posts_link('&laquo; Previous Entries'); ?></div>
			<div class="alignright"><?php previous_posts_link('Next Entries &raquo;'); ?></div>
		</div>
		<?php } ?>

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