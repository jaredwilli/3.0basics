<?php get_header(); ?>
</head>
<body class="<?php base_body_class(); ?>">

<?php include(TEMPLATEPATH . '/branding.php'); ?>

<div id="content" class="inner clearfix">
	<div id="content-main" class="error-404">	
		<h1 class="post-title">Something is missing&hellip;</h1>
		<h3>We're very sorry, but that page doesn't exist or has been moved.</h3>
		<ol>
			<li>Please make sure you have the right URL.</li>
			<li>If you still can't find what you're looking for, try using the search form below.</li>
		</ol>
		<p>We're sorry for any inconvenience.</p>
	
		<div id="searchform">
			<form method="get" id="searchform" action="<?php bloginfo('url'); ?>/">
				<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" value="Search" onFocus="this.value=''" />
				<input type="submit" id="searchsubmit" value="" />
			</form>
		</div><!-- end #searchform -->
	</div><!-- end #content-main -->

	<div id="content-sub"><?php get_sidebar(); ?></div> <!-- end #content-sub -->
</div><!-- end #content -->
<?php get_footer(); ?>