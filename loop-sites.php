<?php if( is_page('sites') ) { ?>

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