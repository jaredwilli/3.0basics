<?php get_header(); ?>
<?php get_template_part( 'branding' ); ?>

<div id="content" class="inner clearfix">
	<div id="content-main">


<?php // get_template_part( 'loop', 'sites' ); ?>

		<?php
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		query_posts( array( 'post_type' => 'site', 'posts_per_page' => 5, 'caller_get_posts' => 1, 'paged' => $paged ) );
		if (have_posts()) : ?>
	
		<?php while (have_posts()) : the_post();
			$s = new TypeSites();
			$a = $s->mshot(250);
		?>

			<div id="site-<?php the_ID(); ?>" class="sites clearfix user_id_<?php the_author_ID(); ?>">
				<div class="site-thumbnail">
					<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
						<?php echo $a[1]; ?>
					</a>
				</div>

				<h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<div class="meta">
					Added by: <?php the_author_posts_link(); ?> on <?php the_time( "F j" ); ?> | <?php comments_popup_link( __( '0 Comments' ), __( '1 Comment' ), __( '% Comments' )); ?>
				</div>
				<div class="entry">
					<?php the_content( __( '(More ...)' )); ?>
				</div>
				<div class="fl post-tags"><?php the_tags( __( ' ' ), ' , ', ' ' ); ?></div>
				<div class="fr edit-link"><?php edit_post_link( __( 'Edit' ) ); ?></div>
			</div>


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