<?php
// Create a variable to the path to functions directory
$functionsdir = TEMPLATEPATH . '/functions';
// Include your posttypes.php file
require_once ( $functionsdir . '/posttypes.php' );

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
/* * * * * * * * * Actions and Filters For Theme * * * * * * * * * * * * * */
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

add_action( 'admin_head', 'admin_register_head' );
add_action( 'get_header', 'redirect_to_first_child', 2 );

add_filter( 'admin_body_class', 'base_admin_body_class' );
add_filter( 'admin_footer_text', 'custom_admin_footer' );

add_action( 'wp_head', 'js_scripts' );
add_filter( 'wp_list_pages','base_better_lists' );
add_filter( 'wp_list_categories','base_better_lists' );
add_filter( 'get_the_excerpt', 'trim_excerpt' );			// remove [...] from excerpts
add_filter( 'the_generator', 'complete_version_removal' ); 	// remove WP version generated in head

// Add 3.0 Theme Supports
add_theme_support( 'menus' );					// support for wp_nav_menu()
// Function for registering wp_nav_menu() in 3 locations
add_action( 'init', 'register_navmenus' );
function register_navmenus() {
	register_nav_menus( array(
		'Top' 		=> __( 'Top Navigation' ),
		'Header'	=> __( 'Header Navigation' ),
		'Footer'	=> __( 'Footer Navigation' ),
		)
	);
}
add_theme_support( 'post-thumbnails' );			// support for post thumbnail feature
add_theme_support( 'automatic-feed-links' );	// support for adding RSS feed links


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
/* * * * * * * * * * Registers Custom Taxonomies * * * * * * * * * * * * * */
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */



/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
/* * * * * * * * * Functions and Custom Settings * * * * * * * * * * * * * */
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

//Add 'first' and 'last' classes to ends of wp_list_pages and wp_list_categories
function base_better_lists($content) {

	$pattern = '/<li class="/is';
	$content = preg_replace($pattern, '<li class="first ', $content, 1);

	$pattern = '/<li class="(?!.*<li class=")/is';
	$content = preg_replace($pattern, '<li class="last ', $content, 1);

	return $content;
}

// Redirect parent pages to first child
function redirect_to_first_child(){
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

// Additional Admin Styles and custom Branding
function admin_register_head() {
	$url = get_bloginfo('template_directory') . '/css/admin.css';
	echo '<link rel="stylesheet" type="text/css" href="' . $url . '" />';
}
function custom_admin_footer() {
	echo '<a href="http://new2wp.com">Theme created by New2WP</a>';
} 

/**
 * Load Scripts
	jQuery 1.4.2	-	jquery
	jQuery UI Core	-	jquery-ui-core
	jQuery UI Tabs 	-	jquery-ui-tabs
	jQuery Thickbox	-	thickbox
	jQuery Tools 	-	jqtools
**/	 
function js_scripts() {
	if( !is_admin() ){
		wp_deregister_script( 'jquery' );
		wp_register_script	( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js', false, '1.4.2', true );
		wp_register_script	( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js', false, '1.8.2', true );
		wp_register_script	( 'jqtools', 
'http://cdn.jquerytools.org/1.2.3/jquery.tools.min.js' );
		wp_enqueue_script	( 'jquery' );
		wp_enqueue_script	( 'jqueryui' );
		wp_enqueue_script	( 'thickbox' );
		
		// load a JS file from my theme: js/theme.js
		wp_enqueue_script	( 'load_script', get_bloginfo('template_url').'/js/global.js', 
				  	array	( 'jquery', 'jqueryui', 'thickbox' ), '1.0', true);
	}
	return;
}

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
/* * * * * * * * * Useful Functions and Features * * * * * * * * * * * * * */
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

// remove [...] from excerpts
function trim_excerpt($text) { return rtrim($text,'[...]'); }

// remove version info from head and feeds
function complete_version_removal() { return ''; }

/**
 * Add support for post thumbnails and add thumb 
 * image to coloumn to post manage page
 *
 * @param, since 2.9
**/
// Add support for post thumbnails, and show them in edit post list
if ( !function_exists('fb_AddThumbColumn') && function_exists('add_theme_support') ) { 
	// for post and page
	add_theme_support('post-thumbnails', array( 'post', 'page' ) );
 
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
			if ( isset($thumb) && $thumb ) {
				echo $thumb;
			} else {
				echo __('None');
			}
		}
	}
	// post thumbnails column on posts manage page
	add_filter( 'manage_posts_columns', 'fb_AddThumbColumn' );
	add_action( 'manage_posts_custom_column', 'fb_AddThumbValue', 10, 2 );
 
	// page thumbnails on pages manage page
	add_filter( 'manage_pages_columns', 'fb_AddThumbColumn' );
	add_action( 'manage_pages_custom_column', 'fb_AddThumbValue', 10, 2 );
}

// List the terms for Categories taxonomy
function category_terms_list() {
	// uses wp_list_categories with Categories taxonomy parameter
	wp_list_categories( array( 
		'style' => 'list', 
		'hide_empty' => 0, 
		'taxonomy' => 'categories', 
		'hierarchical' => true, 
		'title_li' => __( 'Categories' )
		)
	);
	return;
}
// List the terms for Tags taxonomy
function tags_terms_list() {
	// uses wp_list_categories with Tags taxonomy parameter
	wp_list_categories( array( 
		'style' => 'list', 
		'hide_empty' => 0, 
		'taxonomy' => 'post_tags', 
		'hierarchical' => true, 
		'title_li' => __( 'Tags' )
		)
	);
	return;
}

// Get user avatar
function member_get_avatar( $wpcom_user_id, $email, $size, $rating = '', $default = 'http://s.wordpress.com/i/mu.gif' ) {
	if( !empty( $wpcom_user_id ) && $wpcom_user_id !== false && function_exists( 'get_avatar' ) ) {
		return get_avatar( $wpcom_user_id, $size );
	}
	elseif( !empty( $email ) && $email !== false ) {
		$default = urlencode( $default );

		$out = 'http://www.gravatar.com/avatar.php?gravatar_id=';
		$out .= md5( $email );
		$out .= "&amp;size={$size}";
		$out .= "&amp;default={$default}";

		if( !empty( $rating ) ) {
			$out .= "&amp;rating={$rating}";
		}
		return "<img alt='' src='{$out}' class='avatar avatar-{$size}' height='{$size}' width='{$size}' />";
	}
	else {
		return "<img alt='' src='{$default}' />";
	}
}

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */


// admin classes
function base_admin_body_class( $classes ) {
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

// Generates semantic classes for BODY element
function base_body_class( $print = true ) {
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
	if ( is_singular() ) {
		$c[] = 'singular';
	} else {
		$c[] = 'not-singular';
	}

	// Special classes for BODY element when on a single post
	if ( is_single() ) {
		$postID = $wp_query->post->ID;
		the_post();
		// Adds post slug class, prefixed by 'slug-'
		$c[] = 'slug-' . $wp_query->post->post_name;

		// Adds 'single' class and class with the post ID
		$c[] = 'single postid-' . $postID;

		// Adds classes for the month, day, and hour when the post was published
		if ( isset( $wp_query->post->post_date ) )
			thematic_date_classes( mysql2date( 'U', $wp_query->post->post_date ), $c, 's-' );

		// Adds category classes for each category on single posts
		if ( $cats = get_the_category() )
			foreach ( $cats as $cat )
				$c[] = 's-category-' . $cat->slug;

		// Adds tag classes for each tags on single posts
		if ( $tags = get_the_tags() )
			foreach ( $tags as $tag )
				$c[] = 's-tag-' . $tag->slug;


		// Adds taxonomy classes for each tax on single posts
		$taxonomy = get_taxonomy( get_query_var( 'taxonomy' )); 
		$tax = $wp_query->get_queried_object();
		if ( $tax = $wp_query->get_queried_object() )
			foreach ( $tax as $taxi )
				$c[] = 's-tag-' . $tax->slug;

		// Adds MIME-specific classes for attachments
		if ( is_attachment() ) {
			$mime_type = get_post_mime_type();
			$mime_prefix = array( 'application/', 'image/', 'text/', 'audio/', 'video/', 'music/' );
				$c[] = 'attachmentid-' . $postID . ' attachment-' . str_replace( $mime_prefix, "", "$mime_type" );
		}

		// Adds author class for the post author
		$c[] = 's-author-' . sanitize_title_with_dashes(strtolower(get_the_author_login()));
		rewind_posts();
		
		// For posts with excerpts
		if (has_excerpt())
			$c[] = 's-has-excerpt';
			
		// For posts with comments open or closed
		if (comments_open()) {
			$c[] = 's-comments-open';	 
		} else {
			$c[] = 's-comments-closed';
		}
	
		// For posts with pings open or closed
		if (pings_open()) {
			$c[] = 's-pings-open';
		} else {
			$c[] = 's-pings-closed';
		}
	
		// For password-protected posts
		if ( $post->post_password )
			$c[] = 's-protected';
	
		// For sticky posts
		if (is_sticky())
			 $c[] = 's-sticky';	 

	} // end IF is_single()

	// Author name classes for BODY on author archives
	elseif ( is_author() ) {
		$author = $wp_query->get_queried_object();
		$c[] = 'author';
		$c[] = 'author-' . $author->user_nicename;
	}

	// Category name classes for BODY on category archvies
	elseif ( is_category() ) {
		$cat = $wp_query->get_queried_object();
		$c[] = 'category';
		$c[] = 'category-' . $cat->slug;
	}

	// Tag name classes for BODY on tag archives
	elseif ( is_tag() ) {
		$tags = $wp_query->get_queried_object();
		$c[] = 'tag';
		$c[] = 'tag-' . $tags->slug;
	}

	// Taxonomy name classes for BODY on tax archives ---------------- Taxonomy
	if ( is_tax()) {
		$taxonomy = get_taxonomy( get_query_var( 'taxonomy' )); 
		$tax = $wp_query->get_queried_object();
		$taxtitle = $tax->name;
		$c[] = 'tax';
		$c[] = 'tax-' . $tax->slug;
	}
	
	// Page author for BODY on 'pages'
	elseif ( is_page() ) {
		$pageID = $wp_query->post->ID;
		$page_children = wp_list_pages("child_of=$pageID&echo=0");
		the_post();

		// Adds post slug class, prefixed by 'slug-'
		$c[] = 'slug-' . $wp_query->post->post_name;
		$c[] = 'page pageid-' . $pageID;
		$c[] = 'page-author-' . sanitize_title_with_dashes(strtolower(get_the_author('login')));
		
		// Checks to see if the page has children and/or is a child page; props to Adam
		if ( $page_children )
			$c[] = 'page-parent';
		if ( $wp_query->post->post_parent )
			$c[] = 'page-child parent-pageid-' . $wp_query->post->post_parent;
			
		// For pages with excerpts
		if (has_excerpt())
			$c[] = 'page-has-excerpt';
			
		// For pages with comments open or closed
		if (comments_open()) {
			$c[] = 'page-comments-open';		
		} else {
			$c[] = 'page-comments-closed';
		}
	
		// For pages with pings open or closed
		if (pings_open()) {
			$c[] = 'page-pings-open';
		} else {
			$c[] = 'page-pings-closed';
		}
	
		// For password-protected pages
		if ( $post->post_password )
			$c[] = 'page-protected';			
			
		// Checks to see if the page is using a template	
		if ( is_page_template() & !is_page_template('default') )
			$c[] = 'page-template page-template-' . str_replace( '.php', '-php', get_post_meta( $pageID, '_wp_page_template', true ) );
		rewind_posts();
	}

	// Search classes for results or no results
	elseif ( is_search() ) {
		the_post();
		if ( have_posts() ) {
			$c[] = 'search-results';
		} else {
			$c[] = 'search-no-results';
		}
		rewind_posts();
	}

	// For when a visitor is logged in while browsing
	if ( $current_user->ID )
		$c[] = 'loggedin';

	// Paged classes; for 'page X' classes of index, single, etc.
	if ( (( $page = $wp_query->get( 'paged' )) || ( $page = $wp_query->get( 'page' )) ) && $page > 1 ) {
	
		// Thanks to Prentiss Riddle, twitter.com/pzriddle, for the security fix below.
	// Ensures that an integer (not some dangerous script) is passed for the variable
		$page = intval($page);	
		$c[] = 'paged-' . $page;
	
		if ( is_single() ) {
			$c[] = 'single-paged-' . $page;
		} elseif ( is_page() ) {
			$c[] = 'page-paged-' . $page;
		} elseif ( is_category() ) {
			$c[] = 'category-paged-' . $page;
		} elseif ( is_tag() ) {
			$c[] = 'tag-paged-' . $page;

		} elseif ( is_tax() ) { // Is Taxonomy --------Taxonomy paged
			$c[] = 'tax-paged-' . $page;

		} elseif ( is_date() ) {
			$c[] = 'date-paged-' . $page;
		} elseif ( is_author() ) {
			$c[] = 'author-paged-' . $page;
		} elseif ( is_search() ) {
			$c[] = 'search-paged-' . $page;
		}
	}
	
	// A little Browser detection shall we?
	$browser = $_SERVER[ 'HTTP_USER_AGENT' ];
	
	// Mac, PC ...or Linux
	if ( preg_match( "/Mac/", $browser ) ){
		$c[] = 'mac';
		
	} elseif ( preg_match( "/Windows/", $browser ) ){
		$c[] = 'windows';
		
	} elseif ( preg_match( "/Linux/", $browser ) ) {
		$c[] = 'linux';

	} else {
		$c[] = 'unknown-os';
	}
	
	// Checks browsers in this order: Chrome, Safari, Opera, MSIE, FF
	if ( preg_match( "/Chrome/", $browser ) ) {
		$c[] = 'chrome';

		preg_match( "/Chrome\/(\d.\d)/si", $browser, $matches);
		$ch_version = 'ch' . str_replace( '.', '-', $matches[1] );			
		$c[] = $ch_version;

	} elseif ( preg_match( "/Safari/", $browser ) ) {
		$c[] = 'safari';
		
		preg_match( "/Version\/(\d.\d)/si", $browser, $matches);
		$sf_version = 'sf' . str_replace( '.', '-', $matches[1] );			
		$c[] = $sf_version;
			
	} elseif ( preg_match( "/Opera/", $browser ) ) {
		$c[] = 'opera';
		
		preg_match( "/Opera\/(\d.\d)/si", $browser, $matches);
		$op_version = 'op' . str_replace( '.', '-', $matches[1] );			
		$c[] = $op_version;
			
	} elseif ( preg_match( "/MSIE/", $browser ) ) {
		$c[] = 'msie';
		
		if( preg_match( "/MSIE 6.0/", $browser ) ) {
				$c[] = 'ie6';
		} elseif ( preg_match( "/MSIE 7.0/", $browser ) ){
				$c[] = 'ie7';
		} elseif ( preg_match( "/MSIE 8.0/", $browser ) ){
				$c[] = 'ie8';
		}
			
	} elseif ( preg_match( "/Firefox/", $browser ) && preg_match( "/Gecko/", $browser ) ) {
		$c[] = 'firefox';
		
		preg_match( "/Firefox\/(\d)/si", $browser, $matches);
		$ff_version = 'ff' . str_replace( '.', '-', $matches[1] );			
		$c[] = $ff_version;
			
	} else {
		$c[] = 'unknown-browser';
	}
	
	// Separates classes with a single space, collates classes for BODY
	$c = join( ' ', apply_filters( 'body_class', $c ) ); // Available filter: body_class

	// And tada!
	return $print ? print($c) : $c;
}

// Generates time- and date-based classes for BODY, post DIVs, and comment LIs; relative to GMT (UTC)
function thematic_date_classes( $t, &$c, $p = '' ) {
	$t = $t + ( get_option('gmt_offset') * 3600 );
	$c[] = $p . 'y' . gmdate( 'Y', $t ); // Year
	$c[] = $p . 'm' . gmdate( 'm', $t ); // Month
	$c[] = $p . 'd' . gmdate( 'd', $t ); // Day
	$c[] = $p . 'h' . gmdate( 'H', $t ); // Hour
}

// Multiple Sidebars
if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'name'=>'Blog',
		'before_widget' => '<li id="%1$s" class="%2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2>',
		'after_title' => '</h2>'
	));
	register_sidebar(array(
		'name'=>'Page',
		'before_widget' => '<li id="%1$s" class="callout">',
		'after_widget' => '</li>',
		'before_title' => '<h2>',
		'after_title' => '</h2>'
	));
}

// Wordpress 2.7 Legacy Comments
function base_comment($comment, $args, $depth) {
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

// Add is_child() comment Conditional
function is_child($parent) {
	global $wp_query;
	if ($wp_query->post->post_parent == $parent) {
		$return = true;
	}
	else {
		$return = false;
	}
	return $return;
}

?>