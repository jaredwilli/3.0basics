<?php get_header(); ?>
</head>
<body class="<?php base_body_class(); ?>">

<?php include(TEMPLATEPATH . '/branding.php'); ?>

<div id="content" class="inner clearfix">
	<div id="content-main">

	<?php if ( have_posts()) : while (have_posts() ) : the_post(); ?>
	
		<div <?php post_class('clearfix'); ?> id="post-<?php the_ID(); ?>">
			<h1 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
			<div class="entry clearfix">
				<?php the_content(__('Read more'));?>
			</div><!--// end #entry -->
		</div><!--// end #post-<?php the_ID(); ?> -->
	
	<?php endwhile; else : ?>
	
		<h2 class="title">Oops</h2>
		<p>Looks like something is missing...</p>
	
	<?php endif; ?>
	
	</div><!-- end #content-main -->
	<div id="content-sub">
		<?php get_sidebar(); ?>
	</div> <!-- end #content-sub -->

</div><!-- end #content -->
<?php get_footer(); ?>