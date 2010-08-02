<?php
// Initialize the Class and add the action
add_action('init', 'pTypesInit');
function pTypesInit() {
    global $sites;
    $sites = new TypeSites();
}

// Create a post type class for 'Site' posts
// To use as a bookmarking post type for sites you want to save/share.
class TypeSites {

	// Store the data
	public $meta_fields = array( 'title', 'description', 'siteurl', 'category', 'post_tags' );

	// The post type constructor
	public function TypeSites() {

        $siteArgs = array(
			'labels' => array(
                'name' => __( 'Sites', 'post type general name' ),
                'singular_name' => __( 'Site', 'post type singular name' ),
                'add_new' => __( 'Add New', 'site' ),
                'add_new_item' => __( 'Add New Site' ),
                'edit_item' => __( 'Edit Site' ),
                'new_item' => __( 'New Site' ),
                'view_item' => __( 'View Site' ),
                'search_items' => __( 'Search Sites' ),
                'not_found' =>  __( 'No sites found in search' ),
                'not_found_in_trash' => __( 'No sites found in Trash' ),
			),
			'public' => true, 
			'show_ui' => true,
			'_builtin' => false,
			'hierarchical' => false,
			'query_var' => 'site',
			'capability_type' => 'post',
			'rewrite' => array('slug' => 'site'), // Permalinks. Fixes a 404 bug
			'menu_icon'  => get_bloginfo('template_directory').'/images/sites-icon.png',
			'taxonomies' =>  array('category', 'post_tag'), // Add tags and categories taxonomies
			'supports' => array('title','editor','author','comments')
        );
        register_post_type( 'site', $siteArgs );	

	// Initialize the methods
        add_action( 'admin_init', array(&$this, 'admin_init') );
        add_action( 'template_redirect', array(&$this, 'template_redirect') );
        add_action( 'wp_insert_post', array(&$this, 'wp_insert_post'), 10, 2 );

		add_action( 'manage_posts_columns', array ( &$this, 'site_edit_columns' ));
		add_filter( 'manage_posts_custom_column', array( &$this, 'site_custom_columns' ));
	}

	// Create the columns and heading title text
	public function site_edit_columns($columns) {
        $columns = array(
			'cb' 		=> '<input type="checkbox" />',
			'title' 	=> 'Site Title',
			'category'	=> 'Category',
			'post_tags' => 'Tags',
			'siteurl' 	=> 'Thumbnail',
        );
        return $columns;
    }
	// switching cases based on which $column we show the content of it
    public function site_custom_columns($column) { 
		global $post;
        switch ($column) {
            case "title" : the_title();
                break;
            case "category" : get_category_link( $post->ID, 'Categories: ', '', ', ','');
				break;				
            case "post_tags" : get_the_tag_list( $post->ID, 'Tags: ', '', ', ','');
                break;
            case "siteurl" : $m = $this->mshot(100); echo $m[1];
				break;
        }
    }

	// Template redirect for custom templates
    public function template_redirect() {
        global $wp;
        if ( $wp->query_vars["post_type"] == "site" ) {
            include( TEMPLATEPATH . "/single-site.php" ); // a custom single-slug.php template
            die();
        } else {
			$wp_query->is_404 = true;
		}
    }

	// For inserting new 'site' post type posts
    public function wp_insert_post($post_id, $post = null) {
        if ($post->post_type == "site") {
            foreach ($this->meta_fields as $key) {
                $value = @$_POST[$key];
                if (empty($value)) {
                    delete_post_meta($post_id, $key);
                    continue;
                }
                if (!is_array($value)) {
                    if (!update_post_meta($post_id, $key, $value)) {
                        add_post_meta($post_id, $key, $value);
                    }
                } else {
                    delete_post_meta($post_id, $key);
                    foreach ($value as $entry) add_post_meta($post_id, $key, $entry);
                }
            }
        }
    }

	// Add meta box
	function admin_init() {
        add_meta_box( 'sites-meta', 'Site Url (required)', array( &$this, 'meta_options' ), 'site', 'side', 'low' );
    }

	// Admin post meta contents
	public function meta_options() {
		global $post, $url;
		$custom = get_post_custom($post->ID);
		$url = $custom["siteurl"][0];
		$myurl = trailingslashit( get_post_meta( $post->ID, 'siteurl', true ) );
		if ( $myurl != '' ) {
			// Check if url has http:// or not so works either way
			if ( preg_match( "/http(s?):\/\//", $myurl )) {
				$siteurl = get_post_meta( $post->ID, 'siteurl', true );
				$mshoturl = 'http://s.WordPress.com/mshots/v1/' . urlencode( $myurl );
			} else {
				$siteurl = 'http://' . get_post_meta( $post->ID, 'siteurl', true );
				$mshoturl = 'http://s.WordPress.com/mshots/v1/' . urlencode('http://'.$myurl);
			}
			$imgsrc  = '<img src="' . $mshoturl . '?w=250" alt="' . $title . '" title="' . $title . '" width="250" />';
		} ?>

		<p><label>Clean Url: <input id="siteurl" size="26" name="siteurl" value="<?php echo $url; ?>" /></label></p>
		<p><?php echo '<a href="' . $siteurl . '">' . $imgsrc . '</a>'; ?></p>

	<?php
	} // end meta options

    public function mshot($mshotsize) {
        global $post, $url;
        $imgWidth = $mshotsize;
        $myurl = get_post_meta($post->ID, 'siteurl', true);
		if ( $myurl != '' ) {
/* /^ ((http(s)?)+:\/\/)?(www\d?.)?|([a-zA-Z0-9\.\-_])\.+)?([a-zA-Z0-9]+\-?)+(\.\w[2,6])+(\/?([a-zA-Z0-9]+?[\\\/\-\.\?&#%=_]+?\/))?$/
*/
			if ( preg_match( "/http(s?):\/\//", $myurl )) {
				$siteurl = get_post_meta( $post->ID, 'siteurl', true );
				$mshoturl = 'http://s.wordpress.com/mshots/v1/' . urlencode( $myurl );
			} else {
				$siteurl = 'http://' . get_post_meta( $post->ID, 'siteurl', true );
				$mshoturl = 'http://s.wordpress.com/mshots/v1/' . urlencode('http://'.$myurl );
			}
		}
        $mshotimg = '<img src="'.$mshoturl.'?w='.$imgWidth.'" alt="'.get_the_title().'" title="'.get_the_title().'" />';
        
        return array( $siteurl, $mshotimg );
	}
			
} // end of TypeSites{} class
?>