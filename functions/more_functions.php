<?php
/**
 * @3.0 basics
 *
 * Custom snippets for various things
 */
 
/**
 * admin link for all settings
 */ 
function all_settings_link() {
	add_options_page(__('All Settings'), __('All Settings'), 'administrator', 'options.php');
}

/**
 * customize default gravatars
 */
function bb_custom_gravatars($avatar_defaults) {

	// change the default gravatar
	$customGravatar1 = get_bloginfo('template_directory').'/images/default-avatar.png';
	$avatar_defaults[$customGravatar1] = 'Default';

	// add a custom user gravatar
	$customGravatar2 = get_bloginfo('template_directory').'/images/125banner.gif';
	$avatar_defaults[$customGravatar2] = 'Custom Gravatar';

	// add another custom gravatar
	$customGravatar3 = get_bloginfo('template_directory').'/images/2small.png';
	$avatar_defaults[$customGravatar3] = 'Custom gravatar';
	return $avatar_defaults;
}

/**
 * Code Syntax Display Function 
**/
// escape html entities in comments
function encode_code_in_comment($source) {
	$encoded = preg_replace_callback('/<code>(.*?)<\/code>/ims',
	create_function('$matches', '$matches[1] = preg_replace(array("/^[\r|\n]+/i", "/[\r|\n]+$/i"), "", $matches[1]); 
	return "<code>" . htmlentities($matches[1]) . "</"."code>";'), $source);
	if ($encoded)
		return $encoded;
	else
		return $source;
}


// remove nofollow from comments
function xwp_dofollow($str) {
	$str = preg_replace(
		'~<a ([^>]*)\s*(["|\']{1}\w*)\s*nofollow([^>]*)>~U',
		'<a ${1}${2}${3}>', $str);
	return str_replace(array(' rel=""', " rel=''"), '', $str);
}

/**
 */
// Additional Admin Styles and custom Branding
function bb_admin_register_head() {
	$url = get_bloginfo('template_directory') . '/css/admin.css';
	echo '<link rel="stylesheet" type="text/css" href="' . $url . '" />';
}

/**
 */
function bb_custom_admin_footer() {
	echo '<a href="http://new2wp.com">Theme created by New2WP</a>';
} 

/**
 * Remove [...] from excerpts
 */
function trim_excerpt($text) { return rtrim($text,'[...]'); }

/**
 * Remove version info from head and feeds 
 */
function complete_version_removal() { return ''; }

/**
 * List the terms for Categories taxonomy
 */
function bb_category_terms_list() {
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

/**
 * List the terms for Tags taxonomy
 */
function bb_tags_terms_list() {
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

/**
 * Get user avatar
 */
function bb_member_get_avatar( $wpcom_user_id, $email, $size, $rating = '', $default = 'http://s.wordpress.com/i/mu.gif' ) {
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


/**
 * Post pagination function
 *
 * @param integer $range: The range of the slider, works best with even numbers
 * get_pagenum_link($i) - creates the link, e.g. http://site.com/page/4
 * previous_posts_link(' « '); - returns the Previous page link
 * next_posts_link(' » '); - returns the Next page link
 */
function get_pagination( $range = 5 ) {
	// $paged - number of the current page
	global $paged, $wp_query;
	// How much pages do we have?
	if ( !$max_page ) {
		$max_page = $wp_query->max_num_pages;
	}
	// We need the pagination only if there are more than 1 page
	if($max_page > 1){
		if(!$paged){
			$paged = 1;
		}
		// On the first page, don't put the First page link
		if($paged != 1){
			echo "<a href=" . get_pagenum_link(1) . "> First </a>";
		}
		// To the previous page
		previous_posts_link(' &laquo; '); // add before
		// We need the sliding effect only if there are more pages than is the sliding range
		if($max_page > $range){
			// When closer to the beginning
			if($paged < $range){
				for($i = 1; $i <= ($range + 1); $i++){
					echo "<a href='" . get_pagenum_link($i) ."'";
					if($i==$paged) echo "class='current'";
					echo ">$i</a>";
				}
			}
			// When closer to the end
			elseif($paged >= ($max_page - ceil(($range/2)))){
				for($i = $max_page - $range; $i <= $max_page; $i++){
					echo "<a href='" . get_pagenum_link($i) ."'";
					if($i==$paged) echo "class='current'";
					echo ">$i</a>";
				}
			}
			// Somewhere in the middle
			elseif($paged >= $range && $paged < ($max_page - ceil(($range/2)))){
				for($i = ($paged - ceil($range / 2)); $i <= ($paged + ceil(($range/2))); $i++){
					echo "<a href='" . get_pagenum_link($i) ."'";
					if($i==$paged) echo "class='current'";
					echo ">$i</a>";
				}
			}
		}
		// Less pages than the range, no sliding effect needed
		else{
			for($i = 1; $i <= $max_page; $i++){
				echo "<a href='" . get_pagenum_link($i) ."'";
				if($i==$paged) echo "class='current'";
				echo ">$i</a>";
			}
		}
		// Next page
		next_posts_link(' &raquo; '); // add after
		// On the last page, don't put the Last page link
		if($paged != $max_page){
			echo " <a href=" . get_pagenum_link($max_page) . "> Last </a>";
		}
	}
} // end of pagination function()

?>