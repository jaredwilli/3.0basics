<ul id="sidebar">
<?php function showDefault() { // Default Sidebar Content ?>

<!--
	<li id="searchbox">
		<?php get_search_form(); ?>
	</li>


	<li id="nav-level-3">
-->		<?php /*
		global $wp_query;
		$post = $wp_query->post;
		$ancestors = get_post_ancestors($post);
		print_r($ancestors);
		if (empty($post->post_parent)) {
			$parent = $post->ID;
		} else {
			$parent = end($ancestors);
		}
		// If the page is more than 1 level deep build the subnav (don't show on the homepage)
		if($ancestors) {
			// If a page is 3 or more levels deep
			if(count($ancestors) > 1) {
				$section_ID = $ancestors[0];
			// If a page is 2 levels deep
			} else { 
				$section_ID = $post->ID;
			}
			$subnav = wp_list_pages('sort_column=menu_order&title_li=&child_of='.$section_ID.'&echo=0');
		}
		// If the subnav has been built display it
		if ($subnav) {	
			echo $subnav;
		}
		*/?>
<!--
	</li>
-->	
	<li>
		<h2>Pages</h2>
		<ul>
			<?php wp_list_pages('title_li=' ); ?>
		</ul>
	</li>
	<li>
		<h2>Recent Posts</h2>
		<ul>
			<?php get_archives('postbypost', 10); ?>
		</ul>
	</li>
	<li>
		<h2>Monthly Archives</h2>
		<ul>
			<?php wp_get_archives('type=monthly'); ?>
		</ul>
	</li>
	<li>
		<h2>Categories</h2>
		<ul>
			<?php wp_list_categories('show_count=1&hide_empty=1&title_li='); ?>
		</ul>
	</li>
	
	<?php wp_list_bookmarks('&title_li='); ?>
	
<?php } ?>
	
<?php // Load Dynamic Sidebars
if(!function_exists('dynamic_sidebar')) { 
	showDefault();
} else { 
	if(is_page()) {
		if(!dynamic_sidebar('Page')) {
			showDefault();
		}
	} else {
		if(!dynamic_sidebar('Blog')) {
				showDefault();	
		}
	}
}
?>
</ul><!-- end #sidebar -->