<?php
/**
 * The loop that displays posts.
 *
 * The loop displays the posts and the post content.  See
 * http://codex.wordpress.org/The_Loop to understand it and
 * http://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop.php or
 * loop-template.php, where 'template' is the loop context
 * requested by a template. For example, loop-index.php would
 * be used if it exists and we ask for the loop with:
 * <code>get_template_part( 'loop', 'index' );</code>
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?>

<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if ( $wp_query->max_num_pages > 1 ) : ?>

		<?php if( function_exists( 'wp_pagenavi' )) { wp_pagenavi(); } else { ?>
		<div class="navigation clearfix">
			<?php wp_link_pages(); ?>
			<div class="alignleft"><?php next_posts_link('&laquo; Previous Entries'); ?></div>
			<div class="alignright"><?php previous_posts_link('Next Entries &raquo;'); ?></div>
		</div>
		<?php } ?>
		
<?php endif; ?>

<?php /* If there are no posts to display, such as an empty archive page */ ?>
<?php if ( ! have_posts() ) : ?>
	<div id="post-0" class="post error404 not-found">
		<h1 class="entry-title"><?php _e( 'Not Found', 'twentyten' ); ?></h1>
		<div class="entry-content">
			<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'twentyten' ); ?></p>
			<?php get_search_form(); ?>
		</div><!-- .entry-content -->
	</div><!-- #post-0 -->
<?php endif; ?>

<?php
	/* Start the Loop.
	 *
	 * In Twenty Ten we use the same loop in multiple contexts.
	 * It is broken into three main parts: when we're displaying
	 * posts that are in the gallery category, when we're displaying
	 * posts in the asides category, and finally all other posts.
	 *
	 * Additionally, we sometimes check for whether we are on an
	 * archive page, a search page, etc., allowing for small differences
	 * in the loop on each template without actually duplicating
	 * the rest of the loop that is shared.
	 *
	 * Without further ado, the loop:
	 */ ?>
<?php while ( have_posts() ) : the_post(); ?>

	<?php if( is_page() ) { ?>
	
		<div <?php post_class('clearfix'); ?> id="post-<?php the_ID(); ?>">
			<h1 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
			<div class="entry clearfix">
				<?php the_content(__('Read more'));?>
			</div><!--// end #entry -->
		</div><!--// end #post-<?php the_ID(); ?> -->

	<?php } elseif( is_single() ) { ?>
	
		<div class="post" id="post-<?php the_ID(); ?>">
			<h1 class="post-title"><?php the_title(); ?></h1>
			
			<ul class="postinfo clearfix">
				<li class="authordata"><?php the_time('M j, Y'); ?> by <strong><?php the_author(); ?></strong></li>
				<li class="commentdata"><?php comments_popup_link('No Comments', '1 Comment', '% Comments'); ?></li>
			</ul>
			
			<div class="entry clearfix">
				<?php the_content(__('Read more'));?>
			</div><!--// end #entry -->
					<div class="fl post-tags"><?php the_tags( __( ' ' ), ' , ', ' ' ); ?></div>
					<div class="fr edit-link"><?php edit_post_link( __( 'Edit' ) ); ?></div>
		</div><!--// end #post-<?php the_ID(); ?> -->
	
	<?php } else { ?>
	
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

	<?php } ?>

<?php endwhile; // End the loop. Whew. ?>

<?php /* Display navigation to next/previous pages when applicable */ ?>
<?php if (  $wp_query->max_num_pages > 1 ) : ?>

		<?php if( function_exists( 'wp_pagenavi' )) { wp_pagenavi(); } else { ?>
		<div class="navigation clearfix">
			<?php wp_link_pages(); ?>
			<div class="alignleft"><?php next_posts_link('&laquo; Previous Entries'); ?></div>
			<div class="alignright"><?php previous_posts_link('Next Entries &raquo;'); ?></div>
		</div>
		<?php } ?>

<?php endif; ?>

<?php /* <?php if( is_page('sites') ) { ?>

		<?php
		$q = new WP_query();
		$q->query( 'post_type=site' );
		if ($q->have_posts()) : while ($q->have_posts()) : $q->the_post();
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
	<?php endif; ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<?php $s = new TypeSites();
	$a = $s->mshot(600);
	
	//var_dump( $s->mshot(600) );
	//$a = $s->mshot(600);
	//print_r($a[0]);
	?>

	<div id="site-<?php the_ID(); ?>" class="user_id_<?php the_author_ID(); ?>">
		<h2 class="post-title"><a href="<?php echo $a[0]; ?>"><?php the_title(); ?></a></h2>
		<div class="meta">
			Added by: <?php the_author_posts_link(); ?> on <?php the_time("F j"); ?> |
			<?php comments_popup_link( __( '0 Comments' ), __( '1 Comment' ), __( '% Comments' )); ?>
		</div>
			
		<div class="entry">
			<?php the_content( __( '(More ...)' )); ?>
			<div class="fl post-tags"><?php the_tags( __( ' ' ), ' , ', ' ' ); ?></div>
			<div class="fr edit-link"><?php edit_post_link( __( 'Edit' ) ); ?></div>
			<div class="site-bigthumb" align="center">
				<a href="<?php echo $a[0]; ?>" title="<?php the_title(); ?>">
					<?php echo $a[1]; ?>
				</a>
			</div>
		</div>
	</div>
	
	<?php endwhile; ?>

		<?php if( function_exists( 'wp_pagenavi' )) { wp_pagenavi(); } else { ?>
		<div class="navigation clearfix">
			<div class="alignleft"><?php next_posts_link('&laquo; Previous Entries'); ?></div>
			<div class="alignright"><?php previous_posts_link('Next Entries &raquo;'); ?></div>
		</div>
		<?php } ?>

	<?php endif; ?>

<?php } ?>
*/