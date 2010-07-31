<?php get_header(); ?>
</head>
<body class="<?php base_body_class(); ?>">

<?php include(TEMPLATEPATH . '/branding.php'); ?>
<div id="content" class="inner clearfix">
	<div id="content-main">

<?php
// Create list of taxonomy terms and list the posts under each term
$post_type = get_post_type();;
$tax = '';
$tax_terms = get_terms( $tax );
if ($tax_terms) {
	foreach ($tax_terms  as $tax_term) {
	$args = array(
		'post_type' => $post_type,
		"$tax" => $tax_term->slug,
		'post_status' => 'publish',
		'posts_per_page' => -1,
		'caller_get_posts'=> 1
	);

		$my_query = null;
		$my_query = new WP_Query($args);
		
		if( $my_query->have_posts() ) : ?>
		
			<h2 class="breadcrumb">All <?php echo $tax; ?> Posts For <?php echo $tax_term->name; ?></h2>
			<ul class="taxlist">
			<?php while ( $my_query->have_posts() ) : $my_query->the_post(); ?>

				<li id="post-<?php the_ID(); ?>">
					<a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a>
				</li>

			<?php endwhile; // end of loop ?>
			</ul>

			<?php if( function_exists('wp_pagenavi')) { wp_pagenavi(); } else { ?>
				<div class="navigation clearfix">
					<div class="alignleft"><?php next_posts_link('&laquo; Previous Entries') ?></div>
					<div class="alignright"><?php previous_posts_link('Next Entries &raquo;') ?></div>
				</div>
			<?php } ?>
			
		<?php else : ?>
			
			<h2 class="title">Oops</h2>
			<p>Looks like something is missing...</p>

		<?php endif; // if have_posts()
		wp_reset_query();
		
	} // end foreach #tax_terms
}
?>

	</div><!-- end #content-main -->
	<div id="content-sub"><?php get_sidebar(); ?></div> <!-- end #content-sub -->
</div><!-- end #content -->
<?php get_footer(); ?>