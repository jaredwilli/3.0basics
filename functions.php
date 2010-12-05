<?php
/*
/ menubar CSS is totally messed up
=> Menubars are not accepting made-menu

=> scripts need to be enqueued (my_js())
/ should use wordpress jquery enqueued otherwise enqueue the remote
/ functions should have a unique identifier prefix'ing them, specific to the theme example: basics_postthumb();
*/

define( 'N2_INC_PATH', get_template_directory() . '/inc' );
define( 'N2_INC_URL', get_bloginfo('template_directory') . '/inc' );
define( 'N2_FUNC_PATH', get_template_directory() . '/func' ); 
define( 'N2_FUNC_URL', get_bloginfo('template_directory') . '/func' );
define( 'N2_JS_PATH',  get_template_directory() . '/js' );
define( 'N2_JS_URL', get_bloginfo('template_directory' ).'/js' );

// Create a variable to the path to functions directory
$functionsdir 	= TEMPLATEPATH . '/functions';
$jsdir 			= TEMPLATEPATH . '/js';

// Include your posttypes.php file
get_template_part( 'functions/posttypes' );
get_template_part( 'functions/more_functions' );

/** 
 ** Widgets not yet working properly **
get_template_part( 'functions/widgetclasses' );
get_template_part( 'functions/widgets' ); 
*/

/* * * * * * * * * Actions and Filters For Theme * * * * * * * * * * * * * */
add_action( 'init', 'bb_register_navmenus' );
add_action( 'admin_head', 'bb_admin_register_head' );
add_action( 'get_header', 'bb_redirect_to_first_child', 2 );
add_action( 'admin_menu', 'bb_remove_dashboard_boxes' );

add_action( 'right_now_content_table_end', 'bb_right_now_content_table_end' );
add_action( 'manage_users_columns', 'bb_manage_users_columns' );
add_action( 'manage_users_custom_column', 'bb_manage_users_custom_column', 10, 3 );

add_filter( 'admin_body_class', 'bb_base_admin_body_class' );
add_filter( 'admin_footer_text', 'bb_custom_admin_footer' );

add_filter( 'wp_list_pages', 'bb_base_better_lists' );
add_filter( 'wp_list_categories', 'bb_base_better_lists' );
add_filter( 'get_the_excerpt', 'trim_excerpt' );
add_filter( 'the_generator', 'complete_version_removal' );
add_action( 'wp_footer', 'bb_init_method' );

// Add 3.0 Supports
add_theme_support( 'menus' );
add_theme_support( 'post-thumbnails', array( 'post', 'page' ));
add_theme_support( 'automatic-feed-links' );
add_custom_background();

// add_custom_image_header(); // custom image in header
// custom header stuff
// define('HEADER_TEXTCOLOR', '');
// bb_define('HEADER_IMAGE', '%s/lib/styles/images/logo.png' );
// bb_define('HEADER_IMAGE', '' );
// bb_define('HEADER_IMAGE_WIDTH', 960);
// bb_define('HEADER_IMAGE_HEIGHT', 100);
// define('HEADER_IMG_DIR', BB_BASE);
// define('NO_HEADER_TEXT', true);

// add_custom_image_header('bb_adminHeaderStyle', 'bb_adminHeaderStyle');
// function bb_adminHeaderStyle () {}

/**
 *
 * Function for registering wp_nav_menu() in 3 locations
 */
function bb_register_navmenus() {
	register_nav_menus( array(
		'Top' 		=> __( 'Top Navigation' ),
		'Header'	=> __( 'Header Navigation' ),
		'Footer'	=> __( 'Footer Navigation' ),
		)
	);
	
	// Check if Top menu exists and make it if not
	if ( !is_nav_menu( 'Top' )) {
		$menu_id = wp_create_nav_menu( 'Top' );
		$menu = array( 'menu-item-type' => 'custom', 'menu-item-url' => get_home_url('/'),'menu-item-title' => 'Home' );
		wp_update_nav_menu_item( $menu_id, 1, $menu );
	}
	// Check if Header menu exists and make it if not
	if ( !is_nav_menu( 'Header' )) {
		$menu_id = wp_create_nav_menu( 'Header' );
		$menu = array( 'menu-item-type' => 'custom', 'menu-item-url' => get_home_url('/'), 'menu-item-title' => 'Home' );
		wp_update_nav_menu_item( $menu_id, 1, $menu );
	}
	// Check if Footer menu exists and make it if not
	if ( !is_nav_menu( 'Footer' )) {
		$menu_id = wp_create_nav_menu( 'Footer' );
		$menu = array( 'menu-item-type' => 'custom', 'menu-item-url' => get_home_url('/'), 'menu-item-title' => 'Home' );
		wp_update_nav_menu_item( $menu_id, 1, $menu );
	}
	
	// Get any menu locations that dont have a menu assigned to it and give it on
	/*
	$loc = array('Top', 'Header', 'Footer');
	if ( has_nav_menu( $location )) {
		$locations = get_nav_menu_locations();
		return (!empty( $locations[ $location ] ));
	}
	*/
}
/* 
Delete nav menu
wp_delete_nav_menu( $menu );
*/


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
/* * * * * * * * * Functions and Custom Settings * * * * * * * * * * * * * */
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

/**
 *
 * Add 'first' and 'last' classes to ends of wp_list_pages and wp_list_categories
 */
function bb_base_better_lists($content) {
	$pattern = '/<li class="/is';
	$content = preg_replace($pattern, '<li class="first ', $content, 1);
	$pattern = '/<li class="(?!.*<li class=")/is';
	$content = preg_replace($pattern, '<li class="last ', $content, 1);
	return $content;
}

/**
 *
 */
// Redirect parent pages to first child
function bb_redirect_to_first_child(){
	global $post; 
	# UNCOMMENT THE LINE BELOW TO DISABLE FOR CHILD PAGES (ie not to level pages)
	//if($post->post_parent != 0) return;
	
	if( !empty( $post->ID ) ) {
		$pagekids = get_pages("child_of=".$post->ID."&sort_column=menu_order");
		if( function_exists('pods_menu') ) {
			if (is_pod_page()) {
				break;
			}
		} 
		if (!get_post_meta($post->ID, 'dont_redirect', true) && $pagekids && !isset($_GET['s']) && is_page()) {
			$firstchild = $pagekids[0];
			wp_redirect(get_permalink($firstchild->ID));
		}
	}
}


/**
 *
 * Scripts
 * if not admin then javascript - required
 *
if (!is_admin()) {
	wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js' ); 
	wp_enqueue_script ( 'jquery','','','',true );
	wp_register_script( 'myscript', get_bloginfo('template_directory').'/js/global.js' );
	wp_enqueue_script ( 'myscript','','','',true );
	wp_enqueue_script ( 'comment-reply','','','',true );
}
*/

function bb_init_method() {
	echo '<script src="http://code.jquery.com/jquery-1.4.4.min.js"></script>';
	echo '<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js"></script>';
	echo '<script src="'. get_bloginfo("template_directory") .'/js/global.js"></script>';
}


/**
 * Load Scripts
	jQuery 1.4.2	-	jquery
	jQuery UI Core	-	jquery-ui-core
	jQuery UI Tabs 	-	jquery-ui-tabs
	jQuery Thickbox	-	thickbox
	jQuery Tools 	-	jqtools
 */
function bb_scripts () {
	$scripts = array();
	if (!is_admin()) {
		wp_deregister_script( 'jquery' );
		wp_deregister_script( 'jquery-ui-core' );
		
		$scripts = array ( 
			'jquery' => array(
				'jquery', 
				'http://code.jquery.com/jquery-1.4.4.min.js',
			),
			'jquery-ui-core' => array(
				'jquery-ui-core', 
				'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js',
			),
			'jqtools' => array(
				'jqtools', 
				'http://cdn.jquerytools.org/1.2.3/jquery.tools.min.js',
			),
			'global' => array(
				'global', 
				TEMPLATEPATH . '/js/global.js',
			),
			'hoverIntent' => array (
				'hoverIntent',
				TEMPLATEPATH . '/js/hoverIntent.js',
			),
			'superfish' => array (
				'superfish',
				TEMPLATEPATH . '/js/superfish.js',
			),
		);
	}
	return apply_filters ('bb_loadScripts', $scripts);	
}
function bb_loadScripts () {
	$scripts = (array) bb_scriptSettings();
	if (count($scripts) > 0) {
		foreach ($scripts as $script) {
			wp_enqueue_script($script[0], $script[1]);
		}
	}
}

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
/* * * * * * * * * Useful Functions and Features * * * * * * * * * * * * * */
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

// Remove dashboard widgets
function bb_remove_dashboard_boxes() {
	// remove_meta_box( 'dashboard_right_now', 'dashboard', 'core' ); // Right Now Overview 
	// remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'core' ); // Incoming Links
	// remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'core' ); // Recent Comments
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'core' ); // Quick Press Box
	remove_meta_box( 'dashboard_plugins', 'dashboard', 'core' ); // Plugins Box
	remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'core' ); // Recent Drafts Box
	remove_meta_box( 'dashboard_primary', 'dashboard', 'core' ); // WordPress Development 
	remove_meta_box( 'dashboard_secondary', 'dashboard', 'core' ); // Other WordPress News
}


/**
 * generic message for 404 pages
 * Saves adding the same content to many pages
 */
function bb_404() { ?>
	<div class="error message message_404">
		<h2 class="title"><?php _e( 'O_o Great Googly Moogly! Not Found!!' ); ?></h2>
		<p><?php _e( 'What did you lose this time?' ); ?></p>
	</div>
<?php 
}

/**
 * do a 404 header and check file types
 * if bad file type then die properly else continue and print 404 message
 */
function bb_404Response() {
	header('HTTP/1.1 404 Not Found');
	if (!empty($_SERVER['REQUEST_URI'])) {
		$fileExtension = strtolower(pathinfo($_SERVER['REQUEST_URI'], PATHINFO_EXTENSION));
	} else {
		$fileExtension = '';
	}
	$badFileTypes = array( 
		'css', 'txt', 'jpg', 'gif', 'rar', 'zip', 
		'png', 'bmp', 'tar', 'doc', 'xml', 'js', 
		);
	$badFileTypes = apply_filters('bb_404BadFileTypes', $badFileTypes);
	if (in_array($fileExtension, $badFileTypes)) {
		bb_404();
		die();
	}
}

/**
 */
function bb_postthumb() {
	// if post has a thumbnail
	if ( has_post_thumbnail() ) {
		the_post_thumbnail( 'thumbnail' );
	} else { 
		echo '<img src="'. get_bloginfo('template_directory') .'/images/125banner.gif" alt="No Image" class="thumbnail" />';
	}
}

/**
 *
 * Add support for post thumbnails and add thumb 
 * image to coloumn to post manage page
 *
 * @param, since 2.9
**/
if ( !function_exists('fb_AddThumbColumn') && function_exists('add_theme_support') ) { 
	// for post and page
	add_theme_support('post-thumbnails', array( 'post', 'page', 'site' ) );
 
	function fb_AddThumbColumn($cols) { 
		$cols['thumbnail'] = __('Thumbnail');
		return $cols;
	}
	function fb_AddThumbValue( $column_name, $post_id ) { 
		$width = (int) 35;
		$height = (int) 35;

		if ( 'thumbnail' == $column_name ) {
			// thumbnail of WP 2.9
			$thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
			// image from gallery
			$attachments = get_children( array(
				'post_parent' => $post_id, 
				'post_type' => 'attachment', 
				'post_mime_type' => 'image'
				)
			);
			
			if ( $thumbnail_id )
				$thumb = wp_get_attachment_image( $thumbnail_id, array($width, $height), true );
			elseif ( $attachments ) {
				foreach ( $attachments as $attachment_id => $attachment ) {
					$thumb = wp_get_attachment_image( $attachment_id, array($width, $height), true );
				}
			}
			if ( isset($thumb) && $thumb ) { echo $thumb; } else { echo __('None'); }
		}
	}
	// post thumbnails column on posts manage page
	add_filter( 'manage_posts_columns', 'fb_AddThumbColumn' );
	add_action( 'manage_posts_custom_column', 'fb_AddThumbValue', 10, 2 );
 
	// page thumbnails on pages manage page
	add_filter( 'manage_pages_columns', 'fb_AddThumbColumn' );
	add_action( 'manage_pages_custom_column', 'fb_AddThumbValue', 10, 2 );
}

/**
 *
 * Get all of the details for the authors on the blog
 * Most useful for multi author blogs
 */
function bb_listAuthors() {
	global $wpdb;
/*
	$query = 'SELECT COUNT(1) FROM ' . $wpdb->users;
	$count = $wpdb->get_var($query);	
	print_r($count);
*/
	$query = 'SELECT u.ID, u.display_name, u.user_login, u.user_nicename
			FROM ' . $wpdb->users . ' as u
			LEFT JOIN ' . $wpdb->usermeta . ' as m on u.ID = m.user_id
			WHERE m.meta_key = "' . $wpdb->prefix . 'user_level" and m.meta_value > 0';
			
	$authors = $wpdb->get_results($query);	
	$ret = array();
	
	// loop through all authors
	foreach ($authors as $author) {
		$bbWp = new WP_Query();
		$bbWp->query('posts_per_page=4&author=' . $author->ID);
		
		$posts = array();		
		// grab authors latest posts
		if ($bbWp->have_posts()) {
			while ($bbWp->have_posts()) {
				$bbWp->the_post();
				$posts[] = array(
					'title' => get_the_title(),
					'excerpt' => get_the_excerpt(),
					'permalink' => get_permalink(),
				);
			}
		}

		// set author properties
		$ret[] = array(
			'id' => $author->ID,			
			'name' => $author->display_name,
			'username' => $author->user_login,
			'authorPageLink' => get_author_posts_url($author->ID, $author->user_nicename),				
			'posts' => $posts,
		);			
	}
	$ret = apply_filters('bb_listAuthors', $ret);
	return $ret;
}

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

/**
 *
 * Admin classes
 */
function bb_base_admin_body_class( $classes ) {
	if ( is_admin() && isset($_GET['action']) ) {
		$classes .= 'action-'.$_GET['action'];
	}
	if ( is_admin() && isset($_GET['post']) ) {
		$classes .= ' ';
		$classes .= 'post-'.$_GET['post'];
	}
	// Return the $classes array
	return $classes;
}


/**
 *
 * Generates semantic classes for BODY element
 */
function bb_base_body_class( $print = true ) {
	global $wp_query, $current_user;

	// It's surely a WordPress blog, right?
	$c = array('wordpress');

	// Applies the time- and date-based classes (below) to BODY element
	thematic_date_classes( time(), $c );

	// Generic semantic classes for what type of content is displayed
	is_front_page()	? $c[] = 'home' 		: null; // For the front page, if set
	is_home() 		? $c[] = 'blog' 		: null; // For the blog posts page, if set
	is_archive() 	? $c[] = 'archive' 		: null;
	is_date() 		? $c[] = 'date' 		: null;
	is_search() 	? $c[] = 'search' 		: null;
	is_paged() 		? $c[] = 'paged' 		: null;
	is_page()		? $c[] = 'page'			: null;
	is_single()		? $c[] = 'post'			: null;
	is_attachment()	? $c[] = 'attachment' 	: null;
	is_404() 		? $c[] = 'four04' 		: null; // CSS does not allow a digit as first character
	is_tax() 		? $c[] = 'taxonomy' 	: null;
	
	// Special classes for BODY element when a singular post
	if ( is_singular() ) { $c[] = 'singular'; }

	if ( is_single() ) {
	
		$postID = $wp_query->post->ID;
		the_post();
		$c[] = 'slug-' . $wp_query->post->post_name;
		$c[] = 'single postid-' . $postID;
		if ( isset( $wp_query->post->post_date ) )
			thematic_date_classes( mysql2date( 'U', $wp_query->post->post_date ), $c, 's-' );

		// Adds category classes for each category on single posts
		if ( $cats = get_the_category() )
			foreach ( $cats as $cat ) $c[] = 's-category-' . $cat->slug;

		// Adds tag classes for each tags on single posts
		if ( $tags = get_the_tags() )
			foreach ( $tags as $tag ) $c[] = 's-tag-' . $tag->slug;

		/* Adds taxonomy classes for each tax on single posts - Not working right **
		$taxonomy = get_taxonomy( get_query_var( 'taxonomy' )); 
		$tax = $wp_query->get_queried_object();
		if ( $tax = $wp_query->get_queried_object() )
			foreach ( $tax as $taxi ) $c[] = 's-tax-' . $taxi->slug;
		*/

		// Adds MIME-specific classes for attachments
		if ( is_attachment() ) {
		
			$mime_type = get_post_mime_type();
			$mime_prefix = array( 
				'application/', 'image/', 'text/', 
				'audio/', 'video/', 'music/'
			);
			$c[] = 'attach-id-' . $postID . ' attach-'
				. str_replace( $mime_prefix, "", "$mime_type" );
		}

		// Adds author class for the post author
		$c[] = 's-author-' . sanitize_title_with_dashes(strtolower(get_the_author_meta('display_name')));
		rewind_posts();

		if ( has_excerpt() )		$c[] = 's-has-excerpt';
		if ( pings_open() )  		$c[] = 's-pings-open';
		if ( comments_open() ) 		$c[] = 's-comments-open';
		if ( $post->post_password ) $c[] = 's-protected';
		if ( is_sticky() ) 			$c[] = 's-sticky';

	} // end IF is_single()


	elseif ( is_author() ) {
		$author = $wp_query->get_queried_object();
		$c[] = 'author'; $c[] = 'author-' . $author->user_nicename;
	}

	elseif ( is_category() ) {
		$cat = $wp_query->get_queried_object();
		$c[] = 'category'; $c[] = 'category-' . $cat->slug;
	}

	elseif ( is_tag() ) {
		$tags = $wp_query->get_queried_object();
		$c[] = 'tag'; $c[] = 'tag-' . $tags->slug;
	}
	/*
	if ( is_tax()) {
		$taxonomy = get_taxonomy( get_query_var( 'taxonomy' )); 
		$tax = $wp_query->get_queried_object(); 
		$taxtitle = $tax->name; 
		$c[] = 'tax'; $c[] = 'tax-' . $tax->slug;
	}
	*/
	elseif ( is_page() ) {
	
		$pageID = $wp_query->post->ID;
		$page_children = wp_list_pages("child_of=$pageID&echo=0");
		the_post();

		$c[] = 'slug-' . $wp_query->post->post_name;
		$c[] = 'page pageid-' . $pageID;
		$c[] = 'page-author-' . sanitize_title_with_dashes(strtolower(get_the_author_meta('login')));
		
		// Checks to see if the page has children and/or is a child page; props to Adam
		if ( $page_children ) $c[] = 'page-parent';
		if ( $wp_query->post->post_parent ) $c[] = 'page-child parent-pageid-' . $wp_query->post->post_parent;
			
		if (has_excerpt()) $c[] = 'page-has-excerpt';
		if (comments_open()) { $c[] = 'page-comments-open';	}
		if (pings_open()) { $c[] = 'page-pings-open'; }
		if ( $post->post_password ) $c[] = 'page-protected';

		if ( is_page_template() & !is_page_template('default') )
			$c[] = 'page-template page-template-'. str_replace( '.php', '-php', 
				get_post_meta( $pageID, '_wp_page_template', true ) );
		rewind_posts();

	}

	elseif ( is_search() ) {
		the_post();
		if ( have_posts() ) { $c[] = 'search-results'; }
		rewind_posts();
	}
	if ( $current_user->ID ) $c[] = 'loggedin';

	// Paged classes; for 'page X' classes of index, single, etc.
	if( (( $page = $wp_query->get( 'paged' )) 
		|| ( $page = $wp_query->get( 'page' )) 
	  ) && $page > 1 ) {
	
		$page = intval($page); // make sure $page is an integer
		$c[] = 'paged-' . $page;
	
		if ( is_single() ) 		{ $c[] = 'single-paged-' . $page;
		} elseif ( is_page() ) 	{ $c[] = 'page-paged-' . $page;
		} elseif ( is_tag() ) 	{ $c[] = 'tag-paged-' . $page;
		} elseif ( is_tax() ) 	{ $c[] = 'tax-paged-' . $page;
		} elseif ( is_date() ) 	{ $c[] = 'date-paged-' . $page;
		} elseif ( is_author()) { $c[] = 'author-paged-' . $page;
		} elseif ( is_search()) { $c[] = 'search-paged-' . $page;
		} elseif ( is_category()) { $c[] = 'category-paged-' . $page;
		}
	}
	
	// A little Browser detection shall we?
	$browser = $_SERVER[ 'HTTP_USER_AGENT' ];
	// Mac, PC ...or Linux
	if ( preg_match( "/Mac/", $browser ))		 { $c[] = 'mac'; }
	elseif ( preg_match( "/Windows/", $browser)) { $c[] = 'windows'; }
	elseif ( preg_match( "/Linux/", $browser ))  { $c[] = 'linux'; }
	else { $c[] = 'unknown-os'; }
	
	// Checks browsers in this order: Chrome, Safari, Opera, MSIE, FF
	if (preg_match( "/Chrome/", $browser )) 		{ $c[] = 'chrome'; 	/* CHROME */
		preg_match( "/Chrome\/(\d.\d)/si", $browser, $matches);
		$ch_version = 'ch' . str_replace( '.', '-', $matches[1] );			
		$c[] = $ch_version; } 
	elseif (preg_match( "/Safari/", $browser )) 	{ $c[] = 'safari';	/* SAFARI */
			preg_match( "/Version\/(\d.\d)/si", $browser, $matches);
			$sf_version = 'sf' . str_replace( '.', '-', $matches[1] );			
			$c[] = $sf_version; }
	elseif (preg_match( "/Opera/", $browser )) 		{ $c[] = 'opera';	/* OPERA */
			preg_match( "/Opera\/(\d.\d)/si", $browser, $matches);
			$op_version = 'op' . str_replace( '.', '-', $matches[1] );			
			$c[] = $op_version; }
	elseif (preg_match( "/MSIE/", $browser )) 		{ $c[] = 'msie';	/* IE */
		if (preg_match( "/MSIE 6.0/", $browser)) 	{ $c[] = 'ie6'; }	/* IE 6.0 */
		elseif (preg_match("/MSIE 7.0/",$browser)) 	{ $c[] = 'ie7';}	/* IE 7.0 */
		elseif (preg_match("/MSIE 8.0/",$browser)) 	{ $c[] = 'ie8';} 	/* IE 8.0 */
	}
	elseif (preg_match( "/Firefox/", $browser ) && 
			preg_match("/Gecko/", $browser )) 		{ $c[] = 'firefox';	/* FIREFOX */
			preg_match( "/Firefox\/(\d)/si", $browser, $matches);
			$ff_version = 'ff' . str_replace( '.', '-', $matches[1] );
			$c[] = $ff_version; }
	else { 											
		$c[] = 'unknown-browser'; /* UNKNOWN */
	}
	
	// Separates classes with a single space, collates classes for BODY
	$c = join( ' ', apply_filters( 'body_class', $c ) ); // Available filter: body_class
	return $print ? print($c) : $c;

}


/**
 *
 * Generates time- and date-based classes for BODY, post DIVs, and comment LIs; relative to GMT (UTC)
 */
function thematic_date_classes( $t, &$c, $p = '' ) {
	$t = $t + ( get_option('gmt_offset') * 3600 );
	$c[] = $p . 'y' . gmdate( 'Y', $t ); // Year
	$c[] = $p . 'm' . gmdate( 'm', $t ); // Month
	$c[] = $p . 'd' . gmdate( 'd', $t ); // Day
	$c[] = $p . 'h' . gmdate( 'H', $t ); // Hour
}

/**
 *
 * Multiple Sidebars
 */
if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'name'=>'Blog Sidebar',
		'before_widget' => '<li id="%1$s" class="%2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2>',
		'after_title' => '</h2>'
	));
	register_sidebar(array(
		'name'=>'Sites Sidebar',
		'before_widget' => '<li id="%1$s" class="%2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2>',
		'after_title' => '</h2>'
	));
	register_sidebar(array(
		'name'=>'Pages Sidebar',
		'before_widget' => '<li id="%1$s" class="callout">',
		'after_widget' => '</li>',
		'before_title' => '<h2>',
		'after_title' => '</h2>'
	));
}

/**
 *
 * Wordpress 2.7 Legacy Comments
 */
function bb_base_comment($comment, $args, $depth) {
	 $GLOBALS['comment'] = $comment; ?>
	 <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
		<div id="comment-<?php comment_ID(); ?>">
			<div class="author-data">
				<?php echo get_avatar( $comment, $size='40', $default='<path_to_url>' ); ?>
				<?php printf( __( '<h3 class="author">%s</h3>' ), get_comment_author_link() );?>
				<div class="comment-meta commentmetadata">
					<?php printf( __('%1$s at %2$s'), get_comment_date(), get_comment_time() ); ?> 
					<?php edit_comment_link( __('(Edit)'),' ',''); ?>
				</div>
			</div>
<?php if ($comment->comment_approved == '0') : ?>
			<em><?php _e('Your comment is awaiting moderation.') ?></em>
<?php endif; ?>
			<div class="comment-entry">
				<?php comment_text() ?>
			</div>
			<div class="reply">
				<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
			</div>
		</div>
	</li>
<?php
}

/**
 *
 * Add is_child() comment Conditional
 */
function bb_is_child($parent) {
	global $wp_query;
	if ($wp_query->post->post_parent == $parent) {
		$return = true;
	}
	else {
		$return = false;
	}
	return $return;
}


/**
 *
 * Add custom post types to Right Now dashboard widget 
 */
function bb_right_now_content_table_end() {
	$args = array(
		'public' => true ,
		'_builtin' => false
	);
	$output = 'object';
	$operator = 'and';
	$post_types = get_post_types( $args , $output , $operator );
	
	foreach( $post_types as $post_type ) {
		$num_posts = wp_count_posts( $post_type->name );
		$num = number_format_i18n( $num_posts->publish );
		$text = _n( $post_type->labels->singular_name, $post_type->labels->name , intval( $num_posts->publish ) );
		if ( current_user_can( 'edit_posts' ) ) {
			$num = "<a href='edit.php?post_type=$post_type->name'>$num</a>";
			$text = "<a href='edit.php?post_type=$post_type->name'>$text</a>";
		}
		echo '<tr><td class="first b b-' . $post_type->name . '">' . $num . '</td>';
		echo '<td class="t ' . $post_type->name . '">' . $text . '</td></tr>';
	}	
	$taxonomies = get_taxonomies( $args , $output , $operator );
	
	foreach( $taxonomies as $taxonomy ) {
		$num_terms  = wp_count_terms( $taxonomy->name );
		$num = number_format_i18n( $num_terms );
		$text = _n( $taxonomy->labels->singular_name, $taxonomy->labels->name , intval( $num_terms ) );
		if ( current_user_can( 'manage_categories' ) ) {
			$num = "<a href='edit-tags.php?taxonomy=$taxonomy->name'>$num</a>";
			$text = "<a href='edit-tags.php?taxonomy=$taxonomy->name'>$text</a>";
		}
		echo '<tr><td class="first b b-' . $taxonomy->name . '">' . $num . '</td>';
		echo '<td class="t ' . $taxonomy->name . '">' . $text . '</td></tr>';
	}
}


/**
 *
 * Add custom post type counts to the user list panel
 */
function bb_manage_users_columns($column_headers) {
	unset($column_headers['posts']);
	$column_headers['custom_posts'] = 'Assets';
	return $column_headers;
}
function bb_manage_users_custom_column($custom_column, $column_name, $user_id) {
	if ($column_name == 'custom_posts') {
		$counts = _bb_get_author_post_type_counts();
		$custom_column = array();

		if (isset($counts[$user_id]) && is_array($counts[$user_id]))
			foreach($counts[$user_id] as $count) {
				$link = admin_url() . "edit.php/?post_type=" . $count['type'] . "&author=" . $user_id;
				$custom_column[] = "\t<tr><td>{$count['label']}</td><td><a href={$link}>{$count['count']}</</td></tr>";
		}
		$custom_column = implode("\n",$custom_column);

		if (empty($custom_column))
			$custom_column = "<td>0</td>";
		$custom_column = "<table>\n{$custom_column}\n</table>";
	}
	return $custom_column;
}
function _bb_get_author_post_type_counts() {
	static $counts;
	if (!isset($counts)) {
		global $wpdb, $wp_post_types;
		$sql = <<<SQL
		SELECT post_type, post_author, COUNT(*) AS post_count
		FROM {$wpdb->posts} WHERE 1 = 1
		AND post_type NOT IN ('revision', 'nav_menu_item')
		AND post_status IN ('publish', 'pending', 'draft')
		GROUP BY post_type, post_author
SQL;
		$posts = $wpdb->get_results($sql);
		foreach($posts as $post) {
			$post_type_object = $wp_post_types[$post_type = $post->post_type];
			if (!empty($post_type_object->label))
				$label = $post_type_object->label;
			else if (!empty($post_type_object->labels->name))
				$label = $post_type_object->labels->name;
			else
				$label = ucfirst(str_replace(array('-','_'),' ',$post_type));

			if (!isset($counts[$post_author = $post->post_author]))
			$counts[$post_author] = array();
			$counts[$post_author][] = array(
				'label' => $label,
				'count' => $post->post_count,
				'type' => $post->post_type,
			);
		}
	}
	return $counts;
}

?>