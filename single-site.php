<?php get_header(); ?>

</head>
<body class="<?php base_body_class(); ?>">

<?php include(TEMPLATEPATH . '/branding.php'); ?>

<div id="content" class="inner clearfix">

	<div id="content-main">
	
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<?php $s = new TypeSites(); ?>

		<div id="site-<?php the_ID(); ?>" class="user_id_<?php the_author_ID(); ?>">

			<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
			<div class="meta">
				Added by: <?php the_author_posts_link(); ?> on <?php the_time( "F j" ); ?> | <?php comments_popup_link( __( '0 Comments' ), __( '1 Comment' ), __( '% Comments' )); ?>
			</div>
			<div class="entry">
				<?php the_content( __( '(More ...)' )); ?>
			</div>

			<?php edit_post_link( __( 'Edit' ) ); ?><br />
			<?php the_tags( __( 'Tags: ' ), ', ', ' ' ); ?><br />
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php $s->mshot(500); ?></a>

		</div>
	
	<?php comments_template(); ?>

<?php endwhile; else: ?>

	<h1 class="title">Oops</h1>
	<p>Looks like something is missing...</p>

<?php endif; ?>

	</div><!-- end #content-main -->
	<div id="content-sub">
		<?php get_sidebar(); ?>
	</div> <!-- end #content-sub -->
</div><!-- end #content -->
<?php get_footer(); ?>