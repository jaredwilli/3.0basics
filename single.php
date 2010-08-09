<?php get_header(); ?>
<div id="content" class="inner clearfix">
	<div id="content-main" role="main">

	<?php get_template_part( 'loop', 'single' ); ?>


	
	<?php comments_template(); ?>


	</div><!-- end #content-main -->
	<div id="content-sub">
		<?php get_sidebar(); ?>
	</div> <!-- end #content-sub -->
</div><!-- end #content -->
<?php get_footer(); ?>