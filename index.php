<?php get_header(); ?>
</head>
<body class="<?php base_body_class(); ?>">
<?php include( TEMPLATEPATH . '/branding.php' ); ?>

<div id="content" class="inner clearfix">
	<div id="content-main" role="main">

	<?php get_template_part( 'loop', 'index' ); ?>

	</div><!-- end #content-main -->
	<div id="content-sub">
		<?php get_sidebar(); ?>
	</div> <!-- end #content-sub -->
</div><!-- end #content -->
<?php get_footer(); ?>